function startLiveUpdater()
{
    setInterval(getUpdates, 5000);
}

function getUpdates()
{
    theUrl = "/updates";

    // Check if inside group/inbox
    var path = location.pathname.split('/');
    var type = path[2];
    var id = path[3];

    if(type == "group" || type == "inbox")
    {
        theUrl += "/" + type + "/" + id;
    }

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, true ); // true for asynchronous request
    
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
    xmlHttp.send( null );
}

function updateFail()
{
    // Invalid request, die
    // If login expired, this will redirect to login page
    location.href = "/dashboard";
}

function showUpdates(jsonData)
{
    // Show notifications
    document.getElementById("notificationContainer").innerHTML = jsonData["notifications"];

    // Show total notification number per group
    notificationNumbers = jsonData["notificationNumbers"];
    for (var group in notificationNumbers)
    {
        var num = notificationNumbers[group];

        notificationNumDiv = document.getElementsByName(group)[0];
        notificationNumDiv.innerText = num;

        // Hide notification number div if no notifications
        if (num > 0)
            notificationNumDiv.style = "";
        else
            notificationNumDiv.style = "display:none;";
    }

    // Show total message number per inbox
    messageNumbers = jsonData["messageNumbers"];
    for (var inbox in messageNumbers)
    {
        var num = messageNumbers[inbox];

        messageNumDiv = document.getElementsByName(inbox)[0];
        messageNumDiv.innerText = num;

        // Hide message number div if no notifications
        if (num > 0)
            messageNumDiv.style = "";
        else
            messageNumDiv.style = "display:none;";
    }

    // Show post comments
    var posts = jsonData["posts"];
    if (posts != "" && posts != null)
    {
        for (var post in posts)
        {
            // Add comments to each post
            document.getElementById(post).innerHTML = posts[post];
        }
    }

    // Show messages
    var messages = jsonData["messages"]
    if (messages != "" && messages != null)
    {
        document.getElementById("contentInbox").innerHTML = messages;
    }
}

// Start
startLiveUpdater();