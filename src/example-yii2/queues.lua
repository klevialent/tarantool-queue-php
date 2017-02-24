box.cfg {listen=3301}

queue = require('queue')
queue.start()

queue.create_tube('foobar', 'fifo', { if_not_exists=true })
