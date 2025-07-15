from flask import Flask, render_template, request, jsonify
import json
import random

app = Flask(__name__)

# Charger les données JSON
with open("data.json", encoding="utf-8") as f:
    data = json.load(f)

def get_response(user_input):
    for intent in data["intents"]:
        for pattern in intent["patterns"]:
            if pattern.lower() in user_input.lower():
                return random.choice(intent["responses"])
    return "Désolé, je ne comprends pas."

@app.route("/")
def index():
    return render_template("index.html")

@app.route("/chat", methods=["POST"])
def chat():
    user_message = request.json["message"]
    bot_response = get_response(user_message)
    return jsonify({"response": bot_response})

if __name__ == "__main__":
    app.run(debug=True)

