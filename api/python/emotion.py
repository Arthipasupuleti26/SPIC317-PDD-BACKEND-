import sys
import json
import text2emotion as te

sys.stdout.reconfigure(encoding='utf-8')

text = sys.argv[1]

emotions = te.get_emotion(text)

result = {
    "success": True,
    "emotion_happiness": int(emotions["Happy"] * 100),
    "emotion_sadness": int(emotions["Sad"] * 100),
    "emotion_anger": int(emotions["Angry"] * 100),
    "emotion_neutral": int(emotions["Surprise"] * 100)
}

print(json.dumps(result, ensure_ascii=False))
