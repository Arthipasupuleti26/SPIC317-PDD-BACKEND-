import sys
import json
import whisper

if len(sys.argv) < 2:
    print(json.dumps({"error": "No input file"}))
    sys.exit(1)

audio_or_video_file = sys.argv[1]

try:
    model = whisper.load_model("small")  # "small" is good for CPU

    # ✅ CORRECT: Telugu → English
    result = model.transcribe(
        audio_or_video_file,
        task="translate",   # IMPORTANT
        language=None       # auto-detect Telugu
    )

    english = result["text"].strip()

    print(json.dumps({
        "english": english
    }))

except Exception as e:
    print(json.dumps({
        "error": "Whisper STT failed",
        "raw_output": str(e)
    }))
