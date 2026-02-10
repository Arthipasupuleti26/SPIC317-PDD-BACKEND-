import subprocess
import sys
import os
import json

def extract_audio_ffmpeg(video_path, output_path):
    if not os.path.isfile(video_path):
        return False

    cmd = [
        "ffmpeg",
        "-y",
        "-i", video_path,
        "-vn",
        "-acodec", "mp3",
        output_path
    ]

    subprocess.run(
        cmd,
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL
    )
    return os.path.isfile(output_path)


if __name__ == "__main__":
    try:
        video_path = sys.argv[1]
        project_id = sys.argv[2]

        audio_dir = "outputs/audio"
        os.makedirs(audio_dir, exist_ok=True)

        audio_path = f"{audio_dir}/{project_id}.mp3"

        success = extract_audio_ffmpeg(video_path, audio_path)

        if success:
            print(json.dumps({
                "success": True,
                "audio_path": audio_path
            }))
        else:
            print(json.dumps({
                "success": False,
                "error": "Audio extraction failed"
            }))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "error": str(e)
        }))
