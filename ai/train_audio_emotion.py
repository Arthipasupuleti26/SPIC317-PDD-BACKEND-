import os
import librosa
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import LabelEncoder
import joblib

DATASET_PATH = "ravdess_audio"  # folder with wav files

X, y = [], []

def extract_mfcc(file_path):
    y_audio, sr = librosa.load(file_path, sr=16000)
    mfcc = librosa.feature.mfcc(y=y_audio, sr=sr, n_mfcc=40)
    return np.mean(mfcc.T, axis=0)

for file in os.listdir(DATASET_PATH):
    if file.endswith(".wav"):
        emotion_code = int(file.split("-")[2])
        emotion_map = {1: "neutral", 3: "happy", 4: "sad", 5: "angry"}
        if emotion_code not in emotion_map:
            continue

        emotion = emotion_map[emotion_code]
        features = extract_mfcc(os.path.join(DATASET_PATH, file))

        X.append(features)
        y.append(emotion)

X = np.array(X)
y = np.array(y)

le = LabelEncoder()
y = le.fit_transform(y)

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

model = LogisticRegression(max_iter=1000)
model.fit(X_train, y_train)

joblib.dump(model, "audio_emotion_model.pkl")
joblib.dump(le, "audio_emotion_labels.pkl")

print("Audio emotion model trained & saved")
