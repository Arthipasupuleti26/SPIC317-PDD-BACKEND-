from flask import Flask, request, jsonify
import whisper
import os
import subprocess
import asyncio
import uuid
import traceback
import edge_tts
from deep_translator import GoogleTranslator

app = Flask(__name__)

model = whisper.load_model("small")

UPLOAD_DIR = "/content/uploads"
OUTPUT_DIR = "/content/outputs"

os.makedirs(UPLOAD_DIR, exist_ok=True)
os.makedirs(OUTPUT_DIR, exist_ok=True)

@app.route("/dub", methods=["POST"])
def dub():
    try:
        if "video" not in request.files:
            return jsonify({"error": "No file uploaded"})

        file = request.files["video"]

        filename = str(uuid.uuid4()) + ".mp4"
        video_path = os.path.join(UPLOAD_DIR, filename)
        file.save(video_path)

        base = filename.split(".")[0]

        # Convert to clean audio
        clean_audio = f"{OUTPUT_DIR}/{base}_clean.wav"
        subprocess.run(
            f"ffmpeg -y -i {video_path} -ar 16000 -ac 1 {clean_audio}",
            shell=True
        )

        # Transcribe Telugu
        result = model.transcribe(clean_audio, language="te")
        telugu = result["text"].strip()

        if not telugu:
            return jsonify({"error": "No speech detected"})

        # Translate
        english = GoogleTranslator(source="te", target="en").translate(telugu)

        # TTS
        voice_path = f"{OUTPUT_DIR}/{base}_voice.mp3"

        async def speak():
            tts = edge_tts.Communicate(english, voice="en-US-AriaNeural")
            await tts.save(voice_path)

        loop = asyncio.new_event_loop()
        asyncio.set_event_loop(loop)
        loop.run_until_complete(speak())

        # Merge audio + video
        output_video = f"{OUTPUT_DIR}/{base}_dubbed.mp4"

        subprocess.run(
            f"ffmpeg -y -i {video_path} -i {voice_path} -map 0:v -map 1:a -c:v copy -shortest {output_video}",
            shell=True
        )

        return jsonify({
            "telugu": telugu,
            "english": english,
            "video": output_video
        })

    except Exception as e:
        print(traceback.format_exc())
        return jsonify({"error": str(e)})

app.run(port=5000)
