import sys, json, cv2
from deepface import DeepFace

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "no_input_video"}))
        return

    video_path = sys.argv[1]
    cap = cv2.VideoCapture(video_path)

    if not cap.isOpened():
        print(json.dumps({"error": "cannot_open_video"}))
        return

    frame_count = 0
    emotions_count = {}

    # sample every 15th frame
    while True:
        ret, frame = cap.read()
        if not ret:
            break
        frame_count += 1
        if frame_count % 15 != 0:
            continue
        try:
            result = DeepFace.analyze(frame, actions=['emotion'], enforce_detection=False)
            if isinstance(result, list):
                result = result[0]
            dominant = result.get("dominant_emotion", "neutral")
            emotions_count[dominant] = emotions_count.get(dominant, 0) + 1
        except Exception:
            continue

    cap.release()

    if not emotions_count:
        dominant = "neutral"
    else:
        dominant = max(emotions_count, key=emotions_count.get)

    print(json.dumps({"emotion": dominant}))

if __name__ == "__main__":
    main()
