function sendMessage(inboxId)
{
    theUrl = "/add/message";

    // Check if inside group/inbox
    var path = location.pathname.split('/');
    var type = path[2];

    if(type != "inbox")
    {
        // Not called from inside inbox, fail
        updateFail();
    }

    var message = document.getElementById("messageInput").value;
    document.getElementById("messageInput").value = "";

    // Empty message, fail, but don't do updateFail()
    if (message == "")
        return;

    var data = new FormData();
    data.append('inboxId', inboxId);
    data.append('message', message);

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "POST", theUrl, true ); // true for asynchronous request
    
    // Event after request is complete
    xmlHttp.onload = function (e) 
    {
        // Check if request is ok
        if (xmlHttp.readyState === 4) 
        {
            if (xmlHttp.status === 200) 
            {
                jsonData = JSON.parse(xmlHttp.response);

                // If test_var doesn't exist in response, that means something went wrong with the request
                if (jsonData["request"] !== "OK")
                {
                    updateFail();
                }
                else
                {
                    showUpdates(jsonData);
                }
            }
            else
                updateFail();
        }
    }

    // Failed request
    xmlHttp.onerror = function (e) { updateFail(); };

    // Send request
    xmlHttp.send(data);
}