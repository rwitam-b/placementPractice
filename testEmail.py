import re
import sys

import pymysql


def encodeOutput(head, body):
    show = "<div class=\"well text-center myWell\"><h4>" + encodeStrong(head.upper())
    if "class=\"text-danger\">" in body:
        show += " - <span class=\"text-danger glyphicon glyphicon-remove\"></span></h4><br>" + body + "</div>"
    else:
        show += " - <span class=\"text-success glyphicon glyphicon-ok\"></span></h4><br>" + body + "</div>"
    return show


def encodeStrong(s):
    return "<strong>" + s + "</strong>"


def encodeSuccess(s):
    return "<div class=\"text-success\">" + encodeStrong(s) + "</div>"


def encodeFailure(s):
    return "<div class=\"text-danger\">" + encodeStrong(s) + "</div>"


def encodeInfo(s):
    return "<div class=\"text-info\">" + encodeStrong(s) + "</div>"


def checkSubject(sub):
    global found, phrases
    msg = ""
    try:
        if not sub.lower().startswith("subject"):
            raise Exception(encodeFailure("Subject Not Found !<br>The First Line Should Contain The Subject !"))
        subjectWords = sub.split(" ")
        if subjectWords[0] == "Subject:":
            msg = encodeSuccess("Subject Started Properly !")
        elif subjectWords[0].lower() == "subject:":
            tempMsg = "Your Subject Does Not Seem To Have Started Properly !<br>"
            tempMsg += "Email Writing Is Case Sensitive !<br>"
            tempMsg += "\"" + encodeStrong(sub[0:8]) + "\"" + " Should Have Been " + "\""
            tempMsg += encodeStrong("Subject:") + "\""
            raise Exception(encodeFailure(tempMsg))
        else:
            tempMsg = "Your Subject Does Not Seem To Have Started Properly !<br>"
            tempMsg += "Subject Line Should Have Started With \"<strong>Subject: </strong>\""
            tempMsg += "Instead Of \"<strong>" + sub[0:9] + "....</strong>\""
            raise Exception(encodeFailure(tempMsg))
        msg += "<br>"
        if len(sub.strip()) == 8:
            raise Exception(encodeFailure("Subject Started Properly, But No Subject Body Found !"))
        if not sub[-1].isalpha():
            msg += encodeFailure("Subject Should Not Have \"" + encodeStrong(sub[-1]) + "\" At The End !")
            msg += "<br>"
        match = 0
        subjectWords.pop(0)
        for word in data["question"].split(" "):
            for word2 in subjectWords:
                if word.lower().startswith(word2[0:int(len(word2) / 2)].lower()) and len(word2) > 2:
                    match += 1
        for word in subjectWords:
            if word.isalpha() and word in phrases:
                found.append(word)
                phrases.remove(word)
            elif not word[-1].isalpha() and word[0:len(word) - 1] in phrases:
                found.append(word[0:len(word) - 1])
                phrases.remove(word[0:len(word) - 1])
        if match >= len(subjectWords) / 3:
            msg += encodeSuccess("Subject Content Seems Relevant !")
        else:
            msg += encodeFailure("Subject Content Does Not Seem Relevant !")
    except Exception as e:
        msg += str(e) + "<br>"
        msg += encodeFailure("Correct Format -> <pre>Subject:&lt;space>&lt;subject text></pre>")
    return msg


def checkRecipient(receiver):
    msg = ""
    try:
        if len(receiver) < 5:
            raise Exception(encodeFailure("Receiver Not Found !<br>The Second Line Should Address The Recipient !"))
        if receiver.startswith("Dear "):
            msg = encodeSuccess("Receiver Salutation Looks Correct !")
            receiver = receiver[5:]
        elif receiver.lower().startswith("dear "):
            msg = "Receiver Salutation Is Case Sensitive!<br>"
            msg += "\"" + encodeStrong(receiver[0:5]) + "\" Should Have Been \"" + encodeStrong("Dear") + "\" !"
            msg = encodeFailure(msg)
            receiver = receiver[5:]
        else:
            tempMsg = "Receiver Salutation Wrong !<br>"
            tempMsg += "Receiver Line Should Have Started With \"" + encodeStrong("Dear ") + "\" "
            tempMsg += "Instead Of \"" + encodeStrong(receiver[0:5]) + "....\" !"
            raise Exception(encodeFailure(tempMsg))
        msg += "<br>"
        if receiver[-1] != ",":
            msg += encodeFailure("There Should Be A \"" + encodeStrong(",") + "\" After The Recipient Name !") + "<br>"
        else:
            receiver = receiver[0:len(receiver) - 1]
        if data["receiver_type"] == "S":
            tempMsg = "The Receiver Type Here Is " + encodeStrong("Specific")
            tempMsg += ", Meaning That Exact Name Is Provided !"
            msg += encodeInfo(tempMsg)
            if data["receiver"] == receiver:
                msg += encodeSuccess("Receiver Name Correct !")
            else:
                tempMsg = "Receiver Name Incorrect !<br>"
                tempMsg += "\"" + encodeStrong(receiver) + "\" Should Have Been \""
                tempMsg += encodeStrong(data["receiver"]) + "\" !"
                msg += encodeFailure(tempMsg)
        if data["receiver_type"] == "GS":
            tempMsg = "The Receiver Type Here Is " + encodeStrong("Generic Singular")
            tempMsg += ", Meaning That Exact Name Isn't Provided, But It Is Said That The Recipient Is Singular !"
            msg += encodeInfo(tempMsg)
        if data["receiver_type"] == "GP":
            tempMsg = "The Receiver Type Here Is " + encodeStrong("Generic Plural")
            tempMsg += ", Meaning That Exact Name Isn't Provided, But It Is Said That The Recipient Is Plural !"
            msg += encodeInfo(tempMsg)
            msg += "Receiver Name Looks Right !<br>"
        if data["receiver_type"] == "GS" or data["receiver_type"] == "GP":
            if receiver in data["receiver"].split(","):
                msg += encodeSuccess("Receiver Name Looks Correct !")
            else:
                msg += encodeFailure("Receiver Name \"" + encodeStrong(receiver) + "\" Does Not Look Right !")
                tempMsg = "Name Suggestions -> "
                tempMsg += " - ".join(data["receiver"].split(","))
                msg += encodeInfo(tempMsg)
                if receiver.lower() in data["receiver"].lower().split(","):
                    tempMsg = "You Got The Name Correct, But It Is Case Sensitive !<br>"
                    tempMsg += "\"" + encodeStrong(receiver) + "\" Should Have Been \""
                    index = data["receiver"].lower().split(",").index(receiver.lower())
                    tempMsg += encodeStrong(data["receiver"].split(",")[index])
                    tempMsg += "\" !"
                    msg += encodeFailure(tempMsg)
    except Exception as e:
        msg += str(e) + "<br>"
        msg += encodeFailure("Correct Format -> <pre>Dear&lt;space>&lt;receiver name>,</pre>")
    return msg


def checkSender(sender):
    msg = ""
    try:
        if len(sender) == 0:
            raise Exception(encodeFailure("You Did Not Take Leave Properly !"))
        elif len(sender) > 2:
            tempMsg = "Extra Information Found In The Leave Taking Section !<br><pre>"
            for line in sender:
                tempMsg += line + "\n"
            tempMsg += "</pre>"
            raise Exception(encodeFailure(tempMsg))
        elif len(sender) == 1:
            raise Exception(encodeFailure("Inadequate Data In The Leave Taking Section !<br>Sender Details Missing !"))
        else:
            msg = encodeSuccess("Leave Taking Is Appropriate !")
            sender = sender[1]
            if data["sender_type"] == "S":
                tempMsg = "The Sender Type Here Is " + encodeStrong("Specific")
                tempMsg += ", Meaning That Exact Name Is Provided !"
                msg += encodeInfo(tempMsg)
                if data["sender"] == sender:
                    msg += encodeSuccess("Sender Name Correct !")
                else:
                    tempMsg = "Sender Name Incorrect !<br>"
                    tempMsg += "\"" + encodeStrong(sender) + "\" Should Have Been \""
                    tempMsg += encodeStrong(data["sender"]) + "\" !"
                    msg += encodeFailure(tempMsg)
            if data["sender_type"] == "G":
                tempMsg = "The Sender Type Here Is " + encodeStrong("Generic")
                tempMsg += ", Meaning That Exact Name Isn't Provided !"
                msg += encodeInfo(tempMsg)
                if sender in data["sender"].split(","):
                    msg += encodeSuccess("Sender Name Looks Correct !")
                else:
                    msg += encodeFailure("Sender Name \"" + encodeStrong(sender) + "\" Does Not Look Right !")
                    tempMsg = "Name Suggestions -> "
                    tempMsg += " - ".join(data["sender"].split(","))
                    msg += encodeInfo(tempMsg)
                    if sender.lower() in data["sender"].lower().split(","):
                        tempMsg = "You Got The Name Correct, But It Is Case Sensitive !<br>"
                        tempMsg += "\"" + encodeStrong(sender) + "\" Should Have Been \""
                        index = data["sender"].lower().split(",").index(sender.lower())
                        tempMsg += encodeStrong(data["sender"].split(",")[index])
                        tempMsg += "\" !"
                        msg += encodeFailure(tempMsg)
    except Exception as e:
        msg += str(e) + "<br>"
        msg += encodeFailure("Correct Format -> <pre>Regards,\n&lt;sender name></pre>")
    return msg


def checkPhrases(email):
    msg = ""
    global phrases, found
    try:
        for line in email:
            temp = line.split(" ")
            for a in range(0, len(temp)):
                word = temp[a]
                if word.isalpha() and word in phrases:
                    found.append(word)
                    phrases.remove(word)
                elif not word[-1].isalpha() and word[0:len(word) - 1] in phrases:
                    found.append(word[0:len(word) - 1])
                    phrases.remove(word[0:len(word) - 1])
                b = -1
                for x in range(0, len(phrases)):
                    if phrases[x].startswith(word):
                        b = x
                        break
                if b != -1:
                    check = phrases[b].split(" ")
                    flag = True
                    for x in range(0, len(check)):
                        if a + x > len(temp) - 1:
                            flag = False
                            break
                        if temp[a + x].isalpha() and temp[a + x] != check[x]:
                            flag = False
                            break
                        if not temp[a + x][-1].isalpha() and temp[a + x][0:len(temp[a + x]) - 1] != check[x]:
                            flag = False
                            break
                    if flag:
                        found.append(phrases[b])
        temp = data["phrases"].split(",")
        if len(temp) == len(found):
            msg += encodeSuccess("All The Phrases Of The Given Outline Were Used !")
        else:
            tempMsg = "You Missed Out These Phrases -><br><pre>"
            missing = [item for item in temp if item not in found]
            for item in missing:
                tempMsg += " -" + str(item) + "- "
            tempMsg += "</pre>"
            msg += encodeFailure(tempMsg)
    except Exception as e:
        msg = encodeFailure("Error In Phrase Checking")
    return msg


def checkPhraseSequence():
    msg = ""
    try:
        if len(found) == 0:
            raise Exception()
        actual = data["phrases"].split(",")
        misplaced = []
        lastIndex = -1
        for item in found:
            if actual.index(item) != lastIndex + 1:
                misplaced.append(item)
            lastIndex = actual.index(item)
        if len(misplaced) == 0:
            if len(actual) != len(found):
                msg += encodeSuccess("The Phrases Were Being Used In Proper Sequence !")
                msg += encodeFailure("But All The Phrases Were Not Used !")
            else:
                msg += encodeSuccess("The Phrases Are In Proper Sequence !")
        else:
            tempMsg = "The Sequence Of The Following Phrases Were Not Maintained !<br><pre>"
            for x in misplaced:
                tempMsg += " -" + str(x) + "- "
            tempMsg += "\n\nBetter Try And Maintain The Order !</pre>"
            msg += encodeFailure(tempMsg)
    except Exception as e:
        msg += encodeFailure("No Phrases Were Used At All !")
    return msg


# the email body
textActual = sys.argv[1]
text = re.sub(r"(<--rb-->)+", "<--rb-->", textActual)
text = re.sub(r"(<sp>)+", " ", text)
text = text.split("<--rb-->")
text = [line.strip() for line in text]
# the question id
q_id = sys.argv[2]
connection = pymysql.connect(host='localhost', port=3306, user='root', password='', db='aotemaildb')
data = {}
found = []
results = []
try:
    cur = connection.cursor()
    sql = "SHOW COLUMNS FROM email_questions"
    cur.execute(sql)
    temp = []
    queryResult = cur.fetchall()
    for row in queryResult:
        data[row[0]] = ""
        temp.append(row[0])
    sql = "SELECT * from email_questions WHERE id=%s"
    cur.execute(sql, q_id)
    queryResult = cur.fetchone()
    for cols in queryResult:
        data[temp.pop(0)] = cols
    # data now contains all the stuff extracted from the database in a dictionary
    phrases = data["phrases"].split(",")
    cur.close()
    connection.close()
    queryResult = {}
    results.append(encodeOutput("Subject", checkSubject(text[0])))
    index = 0
    if text[0].lower().startswith("subject:"):
        index = 1
        results.append(encodeOutput("Receiver", checkRecipient(text[index])))
        if text[index].lower().startswith("dear"):
            index = 2
    else:
        index = 0
        results.append(encodeOutput("Receiver", checkRecipient(text[index])))
        if text[index].lower().startswith("dear"):
            index = 1
    body = []
    for line in text:
        if "Regards," == line or "Yours faithfully," == line or "Yours sincerely," == line:
            break
        else:
            body.append(line)
    results.append(encodeOutput("Sender", checkSender(text[len(body):])))
    if len(body) == 0:
        raise Exception()
    results.append(encodeOutput("Usage Of Outline Phrases", checkPhrases(body[index:])))
    results.append(encodeOutput("Order Of Outlines Phrases", checkPhraseSequence()))
    for line in results:
        print(line)
except Exception as e:
    print(encodeOutput("Error", encodeFailure("Email Looks Incomplete !")))
