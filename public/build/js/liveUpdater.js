function startLiveUpdater()
{
    setInterval(getUpdates, 5000);
}

function getUpdates()
{
    theUrl = "/updates";

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
                // to-do: maybe scan response before output, or render different template from controller
                document.getElementById("notificationContainer").innerHTML = xmlHttp.responseText;
            
                // If updateRequest div doesn't exist in response, that means something went wrong with the request
                updateRequest = document.getElementById("updateRequest");
                if (updateRequest === null)
                {
                    updateFail();
                }
                else
                {
                    showUpdates();
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

function showUpdates()
{
    // Total notification number for groups inside a hidden div
    notificationNumbers = document.getElementsByClassName("groupNotificationNumber");

    if (notificationNumbers.length == 0)
        alert("not ok");

    for (i = 0; i < notificationNumbers.length; i++)
    {
        divName = notificationNumbers[i].getAttribute('name');

        // Total notification number for group
        num = parseInt(notificationNumbers[i].innerText);

        // Div that shows total notification number for group
        notificationNumDiv = document.getElementsByName(divName)[0];
        notificationNumDiv.innerText = num;

        // Hide notification number div if no notifications
        if (num > 0)
            notificationNumDiv.style = "";
        else
            notificationNumDiv.style = "display:none;";
    }

    // to-do: implement messages
    // messages = document.getElementsByClassName("inboxNotificationNumber");
}

// Start
startLiveUpdater();