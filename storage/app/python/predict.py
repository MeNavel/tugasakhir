import pickle,sys
import cv2
import time
import operator
import warnings
import face_recognition
warnings.filterwarnings("ignore")
from flask import Flask, request

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

@app.route('/predict_face')
def index_face():
    # model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/mobileNet_Face.pkl')
    model=loadModel('/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/python/model/mobileNet_Face_Baru_2.pkl')

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
        return "REJECTED, not valid file , cant be predict"
    face_bounding_boxes = face_recognition.face_locations(img)
    if len(face_bounding_boxes) != 1:
        return "Wajah Tidak Terdeteksi"
    for face_location in face_bounding_boxes:
        top, right, bottom, left = face_location
        face_image = img[top:bottom, left:right]
    img = cv2.resize(face_image, IMG_DIM)
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

    if rank <= 5 and score > 90:
        return "%s" %(huruf)

    else:
        result = "Tidak Terdeteksi"
        return "%s" %(result)


if __name__ == '__main__':
    app.run(debug=True, threaded=True)