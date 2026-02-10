from flask import Flask, request
import requests, json, time, os

API_KEY = "sk_live_USE YOR KEY"
UPLOAD_DIR = r"C:\xampp\htdocs\ai_dub\uploads"

app = Flask(__name__)

@app.get("/process")
def process():
    file = request.args.get("file")
    path = os.path.join(UPLOAD_DIR, file)

    # 1. submit to Hume.ai
    res = requests.post(
        "https://api.hume.ai/v0/batch/jobs",
        headers={"X-Hume-Api-Key": API_KEY},
        files={"files": open(path, "rb")},
        data={"json": json.dumps({"models": {"face": {}, "prosody": {}}})}
    )
    job_id = res.json()["job_id"]

    # 2. poll until done
    while True:
        data = requests.get(
            f"https://api.hume.ai/v0/batch/jobs/{job_id}/predictions",
            headers={"X-Hume-Api-Key": API_KEY}
        ).json()

        if data["state"] == "done":
            break

        time.sleep(2)

    # 3. extract emotion scores
    emotions = data["predictions"][0]["results"]["predictions"][0]["models"]["prosody"]["grouped_predictions"][0]["emotions"]

    formatted = {
        "emotion_happiness": int(emotions.get("joy", 0) * 100),
        "emotion_sadness": int(emotions.get("sadness", 0) * 100),
        "emotion_anger": int(emotions.get("anger", 0) * 100),
        "emotion_neutral": int(emotions.get("calm", 0) * 100)
    }

    # 4. send to PHP
    requests.post(
        "http://localhost/ai_dub/api/video/settings.php",
        json={
            "project_id": 6,
            "voice": "Auto",
            "style": "AutoEmotion",
            **formatted,
            "speed": 100,
            "pitch": 100,
            "subtitle_enabled": 1
        }
    )

    return {"success": True, "stored": formatted}

if __name__ == "__main__":
    app.run(port=5005)
