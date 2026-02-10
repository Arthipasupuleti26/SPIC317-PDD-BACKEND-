import sys, subprocess, os

input_video = sys.argv[1]
output_video = input_video.replace(".mp4", "_final.mp4")
audio = "temp.wav"
tts_audio = "tts.mp3"

# 1️⃣ Extract audio
subprocess.run([
    "ffmpeg","-y","-i",input_video,
    "-vn","-ac","1","-ar","16000",audio
], check=True)

# 2️⃣ Transcribe (fast)
import whisper
model = whisper.load_model("tiny")
result = model.transcribe(audio)
text = result["text"]

# 3️⃣ TTS
from gtts import gTTS
gTTS(text=text, lang="en").save(tts_audio)

# 4️⃣ Merge
subprocess.run([
    "ffmpeg","-y",
    "-i",input_video,
    "-i",tts_audio,
    "-c:v","copy",
    "-map","0:v:0",
    "-map","1:a:0",
    output_video
], check=True)

print("DONE")
