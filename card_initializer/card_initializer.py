import cv2 as cv
import skimage
import matplotlib.pyplot as plt
import os, sys
from skimage.morphology import disk
from scipy.ndimage import binary_fill_holes
from scipy import ndimage
import numpy as np
import re

from pdf2image import convert_from_path

def rotate_image(image, angle):
  image_center = tuple(np.array(image.shape[1::-1]) / 2)
  rot_mat = cv.getRotationMatrix2D(image_center, angle, 1.0)
  result = cv.warpAffine(image, rot_mat, image.shape[1::-1], flags=cv.INTER_CUBIC)
  return result


def cut_card(image,mask,bbox,angle):
    x,y,w,h = bbox
    sub_image = image[y:y+h,x:x+w]
    b_channel, g_channel, r_channel = cv.split(sub_image)
    m = mask[y:y+h,x:x+w]
    alpha_channel = np.zeros_like(m).astype(b_channel.dtype)
    alpha_channel[m==1] = 255

    alpha_image = cv.merge((b_channel, g_channel, r_channel, alpha_channel))
    _border = int(np.max([w,h])*0.5)
    alpha_image = cv.copyMakeBorder(alpha_image,_border,_border,_border,_border,cv.BORDER_CONSTANT,value=(255,255,255,0))
    #alpha_image[:,:,3] = cv.dilate(alpha_image[:,:,3],disk(1))
    alpha_image[:,:,3] = cv.blur(alpha_image[:,:,3],(3,3))


    angle = np.degrees(angle)
    if np.abs(angle)<170:
        alpha_image = rotate_image(alpha_image,angle )

    rotated_mask = np.zeros_like(alpha_image[:,:,3])
    rotated_mask[alpha_image[:,:,3]>0] = 1

    contours,hierarchy = cv.findContours(rotated_mask, 1, 2)
    cnt = contours[0]    
    _bbox = cv.boundingRect(cnt)
    x,y,w,h = _bbox

    alpha_image = alpha_image[y:y+h,x:x+w,:]

    #cv.imshow("card",alpha_image)
    #cv.waitKey()

    return alpha_image

def extract_card_info(img_path, show_mask= False):
    if not os.path.exists(img_path):
        raise IOError

    I = cv.imread(img_path)
    G = cv.cvtColor(I,cv.COLOR_RGB2GRAY)
    #cv.imshow("gray",G)

    #binary = cv.adaptiveThreshold(G, 255, cv.ADAPTIVE_THRESH_MEAN_C , cv.THRESH_BINARY_INV, 3, 3)
    binary = np.zeros(G.shape)
    binary[G<240]=1
    binary = ndimage.binary_fill_holes(binary)
    binary = np.array(binary*255).astype(np.uint8)

    binary = cv.morphologyEx(binary,cv.MORPH_OPEN,kernel=disk(3))        
    #cv.imshow("mask",binary)
    

    mask = np.zeros(G.shape, dtype="uint8")
    labeled_mask,_ = ndimage.label(binary>0)
    _vals, _counts = np.unique(labeled_mask,return_counts = True)
    count_thr = np.max(_counts[1:])*0.2

    cards = []

    for v,c in zip(_vals[1:],_counts[1:]):
        if c<count_thr:
            continue

        mask[labeled_mask==v] = 255
        _mask = np.zeros_like(mask)
        _mask[labeled_mask == v] = 1
        
        contours,hierarchy = cv.findContours(_mask, 1, 2)
        cnt = contours[0]
        rect = cv.minAreaRect(cnt)
        box_points = cv.boxPoints(rect)
        
        bbox = cv.boundingRect(cnt)
        #print(bbox)

        _M = cv.moments(cnt)
        _cX = int(_M["m10"] / _M["m00"])
        _cY = int(_M["m01"] / _M["m00"])

        side_len = 0
        long_side_index = 0
        for _i in range(len(box_points)-1):
            _len = np.array(box_points[_i])-np.array(box_points[_i+1])
            _len = np.linalg.norm(_len)
            if side_len<_len:
                side_len = _len
            else:
                long_side_index = _i-1
                break
        
        _diff = np.array(box_points[long_side_index+1])-np.array(box_points[long_side_index])
        side_vector = _diff / np.linalg.norm(_diff)

        side_angle = np.arccos(np.dot(np.array([0,-1]),side_vector))
        side_angle = side_angle if side_angle<np.pi else np.pi-side_angle

        card_image = cut_card(I,_mask,bbox,side_angle)

        card_info = {}
        card_info["side_angle"] = side_angle
        card_info["long_side"] = np.array([box_points[long_side_index],box_points[long_side_index+1]])
        card_info["center"] = (_cX,_cY)
        card_info["mask"] = _mask
        card_info["bbox"] = bbox
        card_info["image"] = card_image

        cards.append(card_info)

    mask = cv.cvtColor(mask,cv.COLOR_GRAY2BGR)
    for c in cards:
        l = c["long_side"]
        #print(l)
        cv.line(mask,tuple(l[0]),tuple(l[1]),(0,0,255),2)

        up_line_end = (np.array(l[0])-np.array([0,100])).astype(int).tolist()
        #print(up_line_end)
        cv.line(mask,tuple(l[0]),tuple(up_line_end),(0,255,0),2)
        cv.circle(mask,c["center"],radius=3,color=(0,0,255),thickness=2)
        #print(c["side_angle"])

    if show_mask:
        cv.imshow("mask",mask)
        cv.waitKey()

    return cards

def match_faces(card_info_1,card_info_2):
    card_matching_list= []
    if len(card_info_1)!=len(card_info_2):
        raise ValueError
    for ci1 in card_info_1:
        _best_match_value = 0
        _best_match_index = -1
        for i in range(len(card_info_2)):
            ci2 = card_info_2[i]
            match_value = np.sum((ci1["mask"]==1)==(ci2["mask"]==1))
            if match_value > _best_match_value:
                _best_match_value = match_value
                _best_match_index = i
        card_matching_list.append(_best_match_index)
    return card_matching_list

def save_images(card_info_1,card_info_2, out_dir = "", batch_name = "", order = "fb",html_path = ""):
    matching_list = match_faces(card_info_1,card_info_2)
    #print(matching_list)

    for i in range(len(card_info_1)):
        card_1 = card_info_1[i]["image"]
        c1_name= f"{batch_name}_{i+1}_{order[0]}.png"
        cv.imwrite(os.path.join(out_dir,c1_name),card_1)

        matching_card = card_info_2[matching_list[i]]

        card_2 = matching_card["image"]
        c2_name = f"{batch_name}_{i+1}_{order[1]}.png"
        cv.imwrite(os.path.join(out_dir,c2_name),card_2)


        html_string = '<div class="flip-card">\n'
        html_string += f'\t<img class="front-face" src="{html_path}/{c1_name}" alt="?" />\n'
        html_string += f'\t<img class="back-face" src="{html_path}/{c2_name}" alt="?" />\n'
        html_string += '</div>\n'
        
        print(html_string)


def image_path_handling(source_dir,face_mask,back_mask):
    files = os.listdir(source_dir)
    
    F = re.compile(face_mask)
    B = re.compile(back_mask)

    face_images = [ os.path.join(source_dir,f) for f in files if F.match(f) ]
    back_images = [ os.path.join(source_dir,f) for f in files if B.match(f) ]

    face_images = sorted(face_images)
    back_images = sorted(back_images)

    return face_images, back_images


def process_images():
    #konvertió: for i in *.pdf; do pdftoppm $i ${i/.pdf/""} -png; done
    raw_img_dir = os.path.join(os.path.dirname(os.path.dirname(__file__)),"raw_img")
    cards_path = os.path.join(os.path.dirname(os.path.dirname(__file__)),"img","cards")    

    face_images, back_images = image_path_handling(raw_img_dir,".*B.*\.png",".*A.*\.png")

    for i in range(len(face_images)):
        f = face_images[i]
        b = back_images[i]

        f_c = extract_card_info(f,show_mask=False)
        b_c = extract_card_info(b)

        save_images(f_c,b_c,cards_path,i+1,"fb","img/cards")

def test():
    example_path = os.path.join(os.path.dirname(os.path.dirname(__file__)),"private")

    img1_path = os.path.join(example_path,"1.png")
    img2_path = os.path.join(example_path,"2.png")

    img_paths = [img1_path, img2_path]

    c1 = extract_card_info(img1_path)
    c2 = extract_card_info(img2_path)
    
    save_images(c1,c2,"/home/fajtai/")


def main():
    process_images()


if __name__ == "__main__":
    main()