import pickle,sys
import cv2
import time
import operator
import warnings
import face_recognition
warnings.filterwarnings("ignore")

from keras.preprocessing import image
from keras.layers import Dense,GlobalAveragePooling2D,Dropout
from keras.applications.mobilenet import MobileNet
from keras.models import Model
import numpy as np

from flask import Flask, request
from threading import Thread
from tensorflow.keras.models import load_model
from PIL import Image


app = Flask(__name__)

@app.route('/predict_mask')
def index_mask():
    model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/mobileNet_Mask.pkl')
    nmFile = request.args['file']
    r=prediksiImg(nmFile,model)
    return r
def loadModel(nmModel):
    f = open(nmModel, 'rb')
    model = pickle.load(f)
    return model
def createMobileNet():
    DIM=224
    IMG_DIM = (DIM, DIM)
    input_shape = (DIM, DIM, 3)
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
    DIM=224
    IMG_DIM = (DIM, DIM)
    input_shape = (DIM, DIM, 3)
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
    score = round(prob*100,2)
    nmFile = nmFile.replace('/','\\')

    if rank <= 5 and score > 70:
        return "%s" %(huruf)

# @app.route('/predict_face')
# def index_face():
#     # model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/mobileNet_Face.pkl')
#     model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/mobileNet_Face_Baru_2.pkl')

#     nmFile = request.args['file']
#     r=prediksiImg(nmFile,model)
#     return r

# def loadModel(nmModel):
#     f = open(nmModel, 'rb')
#     model = pickle.load(f)
#     return model

# def createMobileNet():
#     DIM=224
#     IMG_DIM = (DIM, DIM)
#     input_shape = (DIM, DIM, 3)
#     import keras
#     from keras.applications.mobilenet import MobileNet
#     from keras.models import Model
#     from tensorflow.keras.backend import clear_session
#     clear_session()
#     mobileNet =  MobileNet(include_top=False, weights='imagenet', 
#                                      input_shape=input_shape)

#     output    = mobileNet.layers[-1].output
#     output    = keras.layers.Flatten()(output)
#     ModelmobileNet = Model(inputs=mobileNet.input, outputs=output)# base_model.get_layer('custom').output)

#     ModelmobileNet.trainable = False
#     for layer in ModelmobileNet.layers:
#         layer.trainable = False
#     return ModelmobileNet

# def prediksiImg(nmFile,model):
#     DIM=224
#     IMG_DIM = (DIM, DIM)
#     input_shape = (DIM, DIM, 3)
#     img = cv2.imread(nmFile)
#     if img is None:
#         return "REJECTED, not valid file , cant be predict"
#     face_bounding_boxes = face_recognition.face_locations(img)
#     if len(face_bounding_boxes) != 1:
#         return "Wajah Tidak Terdeteksi"
#     for face_location in face_bounding_boxes:
#         top, right, bottom, left = face_location
#         face_image = img[top:bottom, left:right]
#     img = cv2.resize(face_image, IMG_DIM)
#     img=img/255
#     img=img.reshape(1,img.shape[0],img.shape[1],img.shape[2])
#     ModelmobileNet = createMobileNet()

#     ftr_np = ModelmobileNet(img)
    
#     predicted_proba = model.predict_proba(ftr_np)
#     res = {}
#     prob = -1
#     for i in range(len(model.classes_)):
#         res[model.classes_[i]] = predicted_proba[0][i]
#     res = sorted(res.items(), key=operator.itemgetter(1))
#     res.reverse()
    
#     rank = 0
#     prev_val = 0
#     huruf = ''
#     for key, val in res:
#         if val >= prev_val:
#             rank += 1
#             prob = val
#             huruf = key
#         prev_val = val
#     score = round(prob*100,2)
#     nmFile = nmFile.replace('/','\\')

#     if rank <= 5 and score > 90:
#         return "%s" %(huruf)

#     else:
#         result = "Tidak Terdeteksi"
#         return "%s" %(result)

@app.route('/predict_face')
def index_face():
    # nmFile = ('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/IMG_6712.JPG')
    nmFile = request.args['file']
    mapClass = ('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/map.npz')
    loaded = np.load(mapClass)
    mapAngka  = loaded['label']
    img_size = 224
    bntk_input = (img_size, img_size, 3)
    kelas=len(mapAngka)
    fold_no=2
    nmModel  = ('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/modelCNN.h5')
    model = mobileNetCNN(bntk_input,kelas)
    model.load_weights(nmModel) 
    img = load_image(nmFile, img_size)
    img = img.reshape(1,224,224,3)
    pred = model(img)
    id_person = np.argmax(pred)
    return(mapAngka[id_person])
    
    # akurasi = np.array(pred)
    # akurasi = akurasi.ravel()
    # return("%s %s"%(mapAngka[id_person], akurasi[id_person]))


def mobileNetCNN(bntk_input,kelas):
    base_model=MobileNet(weights='imagenet',include_top=False, input_shape=bntk_input)

    x=base_model.output
    x=GlobalAveragePooling2D()(x)
    x=Dense(1024,activation='relu')(x) #dense layer 2
    x=Dropout(0.1)(x)   
    x=Dense(512,activation='relu')(x) #dense layer 3   
    x=Dropout(0.1)(x) 
    
    preds=Dense(kelas,activation='softmax')(x) #final layer with softmax activation
    model=Model(inputs=base_model.input,outputs=preds)
    model.compile(optimizer='Adam',#Adam()#0.0001),
                  loss='categorical_crossentropy',
                  metrics=['accuracy'])
    return model

def load_image(img_path, img_size) :
    
    img = image.load_img(img_path, target_size=(img_size, img_size))
    img_tensor = image.img_to_array(img)                    # (height, width, channels)
    img_tensor = np.expand_dims(img_tensor, axis=0)         # (1, height, width, channels), add a dimension because the model expects this shape: (batch_size, height, width, channels)
    img_tensor /= 255.                                      # imshow expects values in the range [0, 1]
    return img_tensor

@app.route('/predict_shield')
def index_shield():
    file = request.args['file']

    # Parameters
    input_size = (150,150)

    #define input shape
    channel = (3,)
    input_shape = input_size + channel

    #define labels
    labels = ['noshield', 'shield']

    # ada 2 cara load model, jika cara pertama berhasil maka bisa lasngusng di lanjutkan ke fungsi prediksi

    MODEL_PATH = '/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/model.h5'
    model = load_model(MODEL_PATH,compile=False)

    # read image
    im = Image.open(file)
    X = preprocess(im,input_size)
    X = reshape([X])
    y = model.predict(X)

    # print( labels[np.argmax(y)], np.max(y) )
    return labels[np.argmax(y)]

def preprocess(img,input_size):
    nimg = img.convert('RGB').resize(input_size, resample= 0)
    img_arr = (np.array(nimg))/255
    return img_arr

def reshape(imgs_arr):
    return np.stack(imgs_arr, axis=0)

if __name__ == '__main__':
    app.run(debug=True, threaded=True)