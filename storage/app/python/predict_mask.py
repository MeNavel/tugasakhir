#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Mon May 31 19:22:42 2021

@author: drajad
"""

import pickle,sys
import cv2
import time
import operator
import warnings
warnings.filterwarnings("ignore")

DIM=224
IMG_DIM = (DIM, DIM)
input_shape = (DIM, DIM, 3)

def loadModel(nmModel):
    f = open(nmModel, 'rb')
    model = pickle.load(f)
    return model

def createMobileNet():
    import keras
    from keras.applications.mobilenet import MobileNet
    from keras.models import Model
    from tensorflow.keras.backend import clear_session
    clear_session()
    mobileNet =  MobileNet(include_top=False, weights='imagenet', 
                                     input_shape=input_shape)

    output    = mobileNet.layers[-1].output
    output    = keras.layers.Flatten()(output)
    ModelmobileNet = Model(inputs=mobileNet.input, outputs=output)# base_model.get_layer('custom').output)

    ModelmobileNet.trainable = False
    for layer in ModelmobileNet.layers:
        layer.trainable = False
    return ModelmobileNet


def prediksiImg(nmFile,model):
    t = time.time()
    img = cv2.imread(nmFile)
    if img is None:
        return t,"REJECTED, not valid file , cant be predict"
    
    img = cv2.resize(img, IMG_DIM)
    img=img/255
    img=img.reshape(1,img.shape[0],img.shape[1],img.shape[2])
    ModelmobileNet = createMobileNet()

    ftr_np = ModelmobileNet(img)
    
    predicted_proba = model.predict_proba(ftr_np)
    res = {}
    prob = -1
    for i in range(len(model.classes_)):
        res[model.classes_[i]] = predicted_proba[0][i]
    res = sorted(res.items(), key=operator.itemgetter(1))
    res.reverse()
    
    rank = 0
    prev_val = 0
    huruf = ''
    for key, val in res:
        if val >= prev_val:
            rank += 1
            prob = val
            huruf = key
        prev_val = val
        # rank += 1
        # if key == huruf:
        #     prob = val
        #     break
    score = round(prob*100,2)
    nmFile = nmFile.replace('/','\\')

    if rank <= 5 and score > 60:
        # result = "ACCEPTED"
        return "%s" %(huruf)

    else:
        result = "Tidak Terdeteksi"
        return "%s" %(result)
    
    # return t,"%s %s mobileNet score %g  rank %g" %(result,huruf,score,rank)
    # return "halo"

if __name__ == '__main__':
    filemodel = sys.argv[2]
    nmFile = sys.argv[1]
    model=loadModel(filemodel)
    r=prediksiImg(nmFile,model)
    print("%s" %(r))
    
    # model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/website/storage/app/python/model/mask/mobileNet_Mask.pkl')
    # nmFile = '/Users/drajad/Desktop/Test/test2.JPG'
    # r=prediksiImg(nmFile,model)
    # print("%s" %(r))
    
