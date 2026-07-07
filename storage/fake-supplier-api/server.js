const express = require('express');
const cors = require('cors');
const { v4: uuidv4 } = require('uuid');
const app = express();
const PORT = process.env.PORT || 4099;

app.use(cors());
app.use(express.json());

const GAMES_DB = {};
for (let i = 1; i <= 204; i++) {
    GAMES_DB[i] = { 
        name: `Game ID ${i}`,
        steam_id: String(100000 + i)
    };
}

const keyPool = {};
Object.keys(GAMES_DB).forEach(id => {
    keyPool[id] = [];
    for (let i = 0; i < 50; i++) {
        keyPool[id].push({
            key: `STEAM-${GAMES_DB[id].steam_id}-${uuidv4().toUpperCase().substring(0, 8)}-${String(i).padStart(4, '0')}`,
            used: false
        });
    }
});

let totalSold = 0;
let totalFailed = 0;

function apiAuth(req, res, next) {
    const apiKey = req.headers['x-api-key'];
    if (!apiKey || apiKey !== 'SUPPLIER_DEMO_KEY_2026') {
        return res.status(401).json({
            success: false,
            error: 'Unauthorized - Invalid API Key'
        });
    }
    next();
}

app.get('/api/health', (req, res) => {
    res.json({
        status: 'ok',
        timestamp: new Date().toISOString(),
        service: 'Fake Supplier API v2.0 (204 Games)',
        stats: {
            total_keys_available: Object.values(keyPool).flat().filter(k => !k.used).length,
            total_sold: totalSold,
            total_failed: totalFailed,
            games_available: Object.keys(GAMES_DB).length
        }
    });
});

app.post('/api/purchase', apiAuth, (req, res) => {
    const { game_id, quantity = 1, customer_email } = req.body;

    if (Math.random() < 0.3) {
        totalFailed++;
        return res.status(502).json({
            success: false,
            error: 'Supplier API temporarily unavailable. (Simulated 30% Error)',
            error_code: 'SUPPLIER_TIMEOUT',
            transaction_id: uuidv4()
        });
    }

    if (!game_id) {
        return res.status(400).json({ success: false, error: 'Missing required field: game_id' });
    }

    if (!GAMES_DB[game_id]) {
        return res.status(404).json({ success: false, error: `Game with id ${game_id} not found` });
    }

    if (quantity < 1 || quantity > 10) {
        return res.status(400).json({ success: false, error: 'Quantity must be between 1 and 10' });
    }

    const pool = keyPool[game_id];
    const availableKeys = pool.filter(k => !k.used);

    if (availableKeys.length < quantity) {
        totalFailed++;
        return res.status(409).json({
            success: false,
            error: `Insufficient keys. Only ${availableKeys.length} left`,
            error_code: 'OUT_OF_STOCK'
        });
    }

    const transactionId = uuidv4();
    const keys = [];
    for (let i = 0; i < quantity; i++) {
        availableKeys[i].used = true;
        keys.push(availableKeys[i].key);
    }
    totalSold += quantity;

    res.json({
        success: true,
        transaction_id: transactionId,
        game_id: game_id,
        game_name: GAMES_DB[game_id].name,
        quantity: quantity,
        keys: keys,
        purchased_at: new Date().toISOString()
    });
});

app.get('/api/games', apiAuth, (req, res) => {
    const games = Object.entries(GAMES_DB).map(([id, info]) => ({
        id: parseInt(id),
        name: info.name,
        steam_id: info.steam_id,
        available_keys: keyPool[id].filter(k => !k.used).length
    }));
    res.json({ success: true, games });
});

app.post('/api/verify-key', apiAuth, (req, res) => {
    const { key_code } = req.body;
    if (!key_code) {
        return res.status(400).json({ success: false, error: 'Missing key_code' });
    }

    for (const [gameId, pool] of Object.entries(keyPool)) {
        const found = pool.find(k => k.key === key_code);
        if (found) {
            return res.json({
                success: true,
                key_code: key_code,
                game_id: parseInt(gameId),
                game_name: GAMES_DB[gameId].name,
                status: found.used ? 'used' : 'available',
                verified_at: new Date().toISOString()
            });
        }
    }

    res.json({ success: false, error: 'Key not found in supplier system' });
});

app.post('/api/reset', apiAuth, (req, res) => {
    Object.keys(keyPool).forEach(id => {
        keyPool[id].forEach(k => k.used = false);
    });
    totalSold = 0;
    totalFailed = 0;
    res.json({ success: true, message: 'Supplier system has been reset' });
});

app.listen(PORT, () => {
    console.log(`╔══════════════════════════════════════════════╗`);
    console.log(`║      FAKE SUPPLIER API - 204 GAMES MODE      ║`);
    console.log(`╠══════════════════════════════════════════════╣`);
    console.log(`║  Port:         ${PORT}                          ║`);
    console.log(`║  API Key:      SUPPLIER_DEMO_KEY_2026        ║`);
    console.log(`║  30% simulated error rate                    ║`);
    console.log(`║  50 keys per game (Total: 10,200 keys)       ║`);
    console.log(`╚══════════════════════════════════════════════╝`);
});