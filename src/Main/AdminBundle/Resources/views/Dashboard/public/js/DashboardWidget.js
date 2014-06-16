var myClank = Clank.connect("ws://localhost:8080");

myClank.on("socket/connect", function(session){
    //session is an Autobahn JS WAMP session.

    console.log("Successfully Connected!");
})

myClank.on("socket/disconnect", function(error){
    //error provides us with some insight into the disconnection: error.reason and error.code

    console.log("Disconnected for " + error.reason + " with code " + error.code);
})