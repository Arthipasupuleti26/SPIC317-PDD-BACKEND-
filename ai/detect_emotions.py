import sys
import subprocess
import cv2
import librosa
import numpy as np
from deepface import DeepFace
import json

# ==================================================
# SAFE INPUT
# ==================================================
try:
    video_path = sys.argv[1]
    audio_path = sys.argv[2]
except:
    print(json.dumps({
        "audio_top2": [{"emotion": "neutral", "confidence": 100}],
        "face_top2": [{"emotion": "neutral", "confidence": 100}],
        "final_emotion": "neutral"
    }))
    sys.exit(0)

# ==================================================
# AUDIO → WAV
# ==================================================
try:
    if not audio_path.lower().endswith(".wav"):
        wav_path = audio_path.rsplit(".", 1)[0] + ".wav"
        subprocess.run(
            ["ffmpeg", "-y", "-i", audio_path, "-ac", "1", "-ar", "16000", wav_path],
            stdout=subprocess.DEVNULL,
            stderr=subprocess.DEVNULL
        )
        audio_path = wav_path
except:
    pass

# ==================================================
# AUDIO EMOTION (4-CLASS LOGIC)
# ==================================================
try:
    y, sr = librosa.load(audio_path, sr=16000)

    energy = float(np.mean(np.abs(y)))
    zcr = float(np.mean(librosa.feature.zero_crossing_rate(y)))
    pitch = float(np.mean(librosa.yin(y, fmin=80, fmax=400)))

    variation = energy + zcr + (pitch / 400)
    confidence = min(100, round(variation * 120, 2))

    if variation > 0.16:
        audio_top2 = [
            {"emotion": "happy", "confidence": confidence},
            {"emotion": "neutral", "confidence": 100 - confidence}
        ]
    elif variation > 0.11:
        audio_top2 = [
            {"emotion": "angry", "confidence": confidence},
            {"emotion": "neutral", "confidence": 100 - confidence}
        ]
    elif variation > 0.07:
        audio_top2 = [
            {"emotion": "neutral", "confidence": 70},
            {"emotion": "sad", "confidence": 30}
        ]
    else:
        audio_top2 = [
            {"emotion": "sad", "confidence": 65},
            {"emotion": "neutral", "confidence": 35}
        ]

except:
    audio_top2 = [
        {"emotion": "neutral", "confidence": 100}
    ]

# ==================================================
# FACE EMOTION (MULTI-FRAME + 4-CLASS MAP)
# ==================================================
FACE_MAP = {
    "happy": "happy",
    "surprise": "happy",
    "angry": "angry",
    "disgust": "angry",
    "sad": "sad",
    "fear": "sad",
    "neutral": "neutral"
}

face_scores = {}

try:
    cap = cv2.VideoCapture(video_path)
    frame_count = 0

    while cap.isOpened() and frame_count < 15:
        ret, frame = cap.read()
        if not ret:
            break

        frame_count += 1
        if frame_count % 5 != 0:
            continue  # sample every 5th frame

        try:
            frame = cv2.resize(frame, (224, 224))
            res = DeepFace.analyze(
                frame,
                actions=["emotion"],
                detector_backend="opencv",   # fast for Android
                enforce_detection=False
            )

            for emo, val in res[0]["emotion"].items():
                mapped = FACE_MAP.get(emo, "neutral")
                face_scores[mapped] = face_scores.get(mapped, 0) + val

        except:
            continue

    cap.release()
except:
    pass

if face_scores:
    sorted_face = sorted(face_scores.items(), key=lambda x: x[1], reverse=True)[:2]
    face_top2 = [
        {"emotion": e, "confidence": round(c, 2)} for e, c in sorted_face
    ]
else:
    face_top2 = [
        {"emotion": "neutral", "confidence": 100}
    ]

# ==================================================
# FINAL EMOTION (CORRECT FUSION – NEUTRAL NEVER DOMINATES)
# ==================================================
face_emotion = face_top2[0]["emotion"]
face_conf = face_top2[0]["confidence"]

audio_emotion = audio_top2[0]["emotion"]
audio_conf = audio_top2[0]["confidence"]

if face_emotion != "neutral" and face_conf >= 60:
    final_emotion = face_emotion
elif audio_emotion != "neutral" and audio_conf >= 50:
    final_emotion = audio_emotion
else:
    final_emotion = "neutral"

# ==================================================
# GUARANTEED JSON OUTPUT
# ==================================================
print(json.dumps({
    "audio_top2": audio_top2,
    "face_top2": face_top2,
    "final_emotion": final_emotion
}))
sys.exit(0)
