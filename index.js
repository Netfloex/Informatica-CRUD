console.clear();
console.log("Starting...")
const qrcode = require("qrcode-terminal");
const fs = require("fs")
fs.rmSync("./db.json")
const { Client } = require("whatsapp-web.js");

const low = require('lowdb')
const FileSync = require('lowdb/adapters/FileSync')
const EventEmitter = require("events");

const events = new EventEmitter();
const db = low(new FileSync('db.json'))
const numbers = require("./stalk.json")
db.defaults({ users: {} })
    .write()
events.on("isOnline", function (data) {
    console.log(data);
    const id = data.number
    if (!db.has(`users.${id}`).value()) {
        db.set(`users.${id}`, {
            name: data.name,
            deniesLastSeen: data.deniesLastSeen,
            log: []
        }).write()
    }
    db.get(`users.${id}.log`).push({
        online: data.online,
        lastSeen: data.lastSeen,
        since: data.online ? new Date().getTime() : data.lastSeen ?? new Date().getTime(),
        startup: !data.hasChanges,
        logTime: new Date().getTime(),
    }).write()
});

var loggedIn = false;
var session;
if (fs.existsSync("session.json")) {
    session = JSON.parse(fs.readFileSync("session.json").toString());
}
const client = new Client({
    session,
    restartOnAuthFail: true,
    // puppeteer: {
    //     headless: false,
    //     devtools: true
    // }
});

client.on('qr', qr => {

    loggedIn = true
    console.clear();
    qrcode.generate(qr, { small: true });
});

client.on("authenticated", session => {


    loggedIn = true
    fs.writeFileSync("session.json", JSON.stringify(session));
})
client.on('ready', async () => {
    loggedIn = true
    console.clear();
    console.log('Client is ready!');
    console.log(client);
    client.pupPage.evaluate("Store.Conn.battery").then(battery => {
        console.log(`Batterij: ${battery}`)
    });
    await client.pupPage.exposeFunction("emitter", (...data) =>
        events.emit(...data)
    );
    await client.pupPage.evaluate(async (numbers) => {
        var openChat = new (mR.findModule((module) => module.default && module.default.prototype && module.default.prototype.openChat)[0].default)().openChat;
        function stalk(number) {
            const pres = Store.Presence.get(`${number}@c.us`)
            if (!pres) {

                openChat(`${number}@c.us`)
                setTimeout(() => stalk(number), 1000)
                console.log(`${number} even meer moeite`)
                return;
            }


            function call(bool) {
                if (!pres.hasData) return;
                if (!pres.chatstate.t) {
                    console.log(pres.chatstate);
                    console.log(number, "type", pres.type, "stale", pres.stale, "isState", pres.isState);
                }

                emitter("isOnline", {
                    hasChanges: bool != undefined,
                    online: pres.isOnline,
                    lastSeen: pres.chatstate.t * 1000,
                    name: Store.Contact.get(`${number}@c.us`).displayName,
                    deniesLastSeen: pres.chatstate.deny,
                    number,
                });

            }
            pres.subscribe()
            pres.on("change:isOnline", call)
            setTimeout(call, 1000)
        }

        numbers.forEach(stalk)
    }, numbers)
});


client.on("message", message => {
    if (message.body == "test") {
        message.reply("Ik ben er hoor!")
    }
})
setTimeout(() => {
    if (!loggedIn) {
        if (fs.existsSync("session.json"))
            fs.rm("session.json")
        console.log("Please restart!")
        process.exit(-1)
    }
}, 10000)
client.initialize();