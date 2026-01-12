import sys
import pyttsx3
from pathlib import Path

text = sys.argv[1]
emotion = sys.argv[2]
output = sys.argv[3]

engine = pyttsx3.init()

# Emotion-based voice control
if emotion == "sad":
    engine.setProperty('rate', 130)
elif emotion == "happy":
    engine.setProperty('rate', 180)
elif emotion == "angry":
    engine.setProperty('rate', 200)
else:
    engine.setProperty('rate', 160)

Path(output).parent.mkdir(parents=True, exist_ok=True)
engine.save_to_file(text, output)
engine.runAndWait()
