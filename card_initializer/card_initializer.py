import cv2 as cv
import skimage
import matplotlib.pyplot as plt
import os, sys
from skimage.morphology import disk
from scipy.ndimage import binary_fill_holes
from scipy import ndimage
import numpy as np


def test():
    example_path = os.path.join(os.path.dirname(os.path.dirname(__file__)),"private")

    img1_path = os.path.join(example_path,"1.png")
    img2_path = os.path.join(example_path,"2.png")

    img_paths = [img1_path, img2_path]

    for img_path in img_paths:
        
        if not os.path.exists(img_path):
            continue

        I = cv.imread(img1_path)
        G = cv.cvtColor(I,cv.COLOR_RGB2GRAY)

        #binary = cv.adaptiveThreshold(G, 255, cv.ADAPTIVE_THRESH_MEAN_C , cv.THRESH_BINARY_INV, 3, 3)
        binary = np.zeros(G.shape)
        binary[G<250]=1
        binary = ndimage.binary_fill_holes(binary)
        binary = np.array(binary*255).astype(np.uint8)

        binary = cv.morphologyEx(binary,cv.MORPH_OPEN,kernel=disk(3))        
        cv.imshow("mask",binary)
        

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
            _mask[labeled_mask == v] = 255
            
            contours,hierarchy = cv.findContours(_mask, 1, 2)
            cnt = contours[0]
            rect = cv.minAreaRect(cnt)
            box_points = cv.boxPoints(rect)
           	
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
            
            #_diff = np.array()-np.array()

            card_info = {}
            card_info["long_side"] = box_points[long_side_index:long_side_index+2]
            card_info["center"] = (_cX,_cY)
            

            cards.append(card_info)

        mask = cv.cvtColor(mask,cv.COLOR_GRAY2BGR)
        for c in cards:
            l = c["long_side"]
            print(l)
            cv.line(mask,tuple(l[0]),tuple(l[1]),(0,0,255),2)

            up_line_end = (np.array(l[0])-np.array([0,100])).astype(int).tolist()
            print(up_line_end)
            cv.line(mask,tuple(l[0]),tuple(up_line_end),(0,255,0),2)
            cv.circle(mask,c["center"],radius=3,color=(0,0,255),thickness=2)
        cv.imshow("mask",mask)

        

        cv.waitKey()


def main():
    test()    


if __name__ == "__main__":
    main()