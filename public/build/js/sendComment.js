function sendComment(postId)
{
    theUrl = "/add/comment";

    // Check if inside group/inbox
    var path = location.pathname.split('/');
    var type = path[2];
    var id = path[3];

    if(type != "group")
    {
        // Not called from inside group, fail
        updateFail();
    }

    var comment = document.getElementById("postInput_" + postId).value;
    document.getElementById("postInput_" + postId).value = "";

    var data = new FormData();
    data.append('groupId', id);
    data.append('groupPostId', postId);
    data.append('text', comment);

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