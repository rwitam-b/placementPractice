import requests
import json
import os
import re
import sys
from docx import Document


def encodeStrong(s):
    return "<strong>" + s + "</strong>"


def encodeOutput(head, body):
    show = "<div class=\"well text-center myWell\"><h4>" + encodeStrong(head.upper())
    show += " - <span class=\"text-info glyphicon glyphicon-list-alt\"></span></h4><br>" + body + "</div>"
    return show


try:
    location = "email_attempts"
    filename = "test.docx"
    inputData = sys.argv[1]
    inputData = re.sub(r"(<--rb-->)+", "\n", inputData)
    inputData = re.sub(r"(<sp>)+", " ", inputData)
    document = Document()
    document.add_paragraph(inputData)
    document.add_page_break()
    document.save(os.path.join(location, filename))
    msg = "<a href=\'" + os.path.join(location, filename) + "' download>Click Here To Download Your Email</a>"
    print(encodeOutput("Grammar & Spelling", "<div class=\"text-danger\">" + encodeStrong(msg) + "</div>"))
    # payload = {'key': 'hdMnhEFayzZqbUTx', 'text': inputData}
    # responseObject = requests.post("https://api.textgears.com/check.php", data=payload)
    # result = []
    # if responseObject.status_code == 200:
    #     jsonString = json.dumps(responseObject.json())  # str type
    #     jsonString.replace("result : true", "result : True")
    #     jsonString.replace("result : false", "result : False")
    #     jsonObject = json.loads(jsonString)  # dict type
    #     if jsonObject["result"]:
    #         for x in jsonObject["errors"]:
    #             if x["bad"] in sys.argv[2]:
    #                 continue
    #             msg = "...."
    #             if x["offset"] > 20:
    #                 msg += inputData[x["offset"] - 21:x["offset"]] + " "
    #             else:
    #                 msg += inputData[0:x["offset"] - 1] + " "
    #             msg += encodeStrong(x["bad"])
    #             msg=msg.replace("\n", "<br>")
    #             result.append([msg, ", ".join(x["better"])])
    #         if len(result) == 0:
    #             result.append(["No Errors Found !", "No Suggestions !"])
    #         htmlcode = HTML.table(result, header_row=["Error", "Suggestions"])
    #         print(encodeOutput("Grammar & Spelling", htmlcode))
    #     else:
    #         errorMessage = "Error Code : " + jsonObject["error_code"] + "<br>" + jsonObject["description"]
    #         raise Exception()
except Exception as e:
    msg = encodeStrong("Could Not Provide This Check Right Now Due To A Technical Glitch !")
    print(encodeOutput("Grammar & Spelling", "<div class=\"text-danger\">" + msg + "</div>"))
