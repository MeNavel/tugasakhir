x=GlobalAveragePooling2D()(x)
x=Dense(1024,activation='relu')(x)
x=Dropout(0.1)(x)   
x=Dense(512,activation='relu')(x)
x=Dropout(0.1)(x)
preds=Dense(kelas,activation='softmax')(x)