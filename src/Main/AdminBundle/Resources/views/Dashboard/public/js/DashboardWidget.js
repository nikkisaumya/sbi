var conn = new ab.Session('ws://localhost:8080',
    function() {
        conn.subscribe('Category', function(topic, data) {
            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            console.log('New article published to category "' + topic + '" : ' + data.title);
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);