<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
            function functionConfirm(msg, myYes, myNo) {
                var confirmBox = $("#confirm");
                confirmBox.find(".message").text(msg);
                confirmBox.find(".yes,.no").unbind().click(function() {
                        confirmBox.hide();
                            });
                        confirmBox.find(".yes").click(myYes);
                        confirmBox.find(".no").click(myNo);
                        confirmBox.show();
            }
        </script>
        <style>
            #confirm {
                display: none;
                background-color: #f2f4ef;
                border: 1px solid #aaa;
                position: fixed;
                width: 250px;
                height: 150px;
                left: 50%;
                margin-left: -100px;
                padding: 8px;
                box-sizing: border-box;
                text-align: center;
            }
        #confirm button {
            background-color: #4aace3;
            display: inline-block;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 5px;
            text-align: center;
            width: 80px;
            cursor: pointer;
            margin-top: 20px;
        }
        #confirm .message {
            text-align: left;
        }
        </style>
    </head>
    <body>
        <div id="confirm">
            <div class="message"></div>
            <button class="yes">Yes</button>
            <button class="no">No</button>
        </div>
        <button onclick = 'functionConfirm("Do you like Cricket?", function yes() {
            alert("Yes")
            },
            function no() {
            alert("No")
            });'>submit</button>
    </body>
</html>