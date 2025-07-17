// Converts ArrayBuffer or TypedArray to base64 string
const bufferToBase64 = (buf: ArrayBuffer | Uint8Array): string => btoa(String.fromCharCode(...new Uint8Array(buf)));

// Converts base64 string back to Uint8Array
const base64ToBuffer = (b64: string): Uint8Array => Uint8Array.from(atob(b64), (c) => c.charCodeAt(0));

export const cryptoService = (() => {
    const SECRET_KEY = import.meta.env.VITE_STORAGE_ENCRYPTION_KEY;
    const rawKey = Uint8Array.from(atob(SECRET_KEY), (c) => c.charCodeAt(0));

    return {
        async encrypt(data: string) {
            const iv = crypto.getRandomValues(new Uint8Array(16));
            const key = await crypto.subtle.importKey('raw', rawKey, { name: 'AES-CBC' }, false, ['encrypt']);
            const ciphertext = await crypto.subtle.encrypt({ name: 'AES-CBC', iv }, key, new TextEncoder().encode(JSON.stringify(data)));
            return bufferToBase64(new Uint8Array([...iv, ...new Uint8Array(ciphertext)]));
        },

        async decrypt(encryptedData: string) {
            const encrypted = base64ToBuffer(encryptedData);
            const iv = encrypted.slice(0, 16);

            const ciphertext = encrypted.slice(16);

            const key = await crypto.subtle.importKey('raw', rawKey, { name: 'AES-CBC' }, false, ['decrypt']);
            const decrypted = await crypto.subtle.decrypt({ name: 'AES-CBC', iv }, key, ciphertext);
            return JSON.parse(new TextDecoder().decode(decrypted));
        },
    };
})();
