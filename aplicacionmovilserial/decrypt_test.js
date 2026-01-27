const crypto = require('crypto');

// Parámetros de cifrado de la app
const key = 'my 32 length key................'; // 32 chars
const iv = 'helloworldhellow'; // 16 chars

// Valor cifrado que devuelve el servidor
const encryptedData = 'lffCjmAspZoMNWv7pF5jSA==';

try {
    const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
    let decrypted = decipher.update(encryptedData, 'base64', 'utf8');
    decrypted += decipher.final('utf8');

    console.log('Datos descifrados:');
    console.log(decrypted);

    try {
        const jsonData = JSON.parse(decrypted);
        console.log('\nJSON parseado:');
        console.log(JSON.stringify(jsonData, null, 2));
    } catch (e) {
        console.log('No es un JSON válido');
    }
} catch (error) {
    console.error('Error al descifrar:', error.message);
}
