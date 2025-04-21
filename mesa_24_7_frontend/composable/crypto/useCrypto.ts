import AES from 'crypto-js/aes'
import Utf8 from 'crypto-js/enc-utf8'
import Base64 from 'crypto-js/enc-base64'

const credKey = import.meta.env.VITE_CLIENT_CRED_ENCRYPT_KEY ?? ''

export function useCrypto() {
  const isEmpty = (v: unknown) => v === null || v === ''

  function encryptAES(data: string, key: string = credKey): string | null {
    if (isEmpty(data)) return null
    return AES.encrypt(data, key).toString()
  }

  function decryptAES(data: string, key: string = credKey): string | null {
    if (isEmpty(data)) return null
    const bytes = AES.decrypt(data, key)
    return bytes.toString(Utf8)
  }

  function decryptData<T = string>(data: string, isJson = false): T | string | null {
    if (isEmpty(data)) return null
    const encrypted = JSON.parse(atob(data.toString()))
    const decrypted = AES.decrypt(encrypted.value, Base64.parse(credKey), {
      iv: Base64.parse(encrypted.iv),
    })
    const result = decrypted.toString(Utf8)
    return isJson ? JSON.parse(result) : result
  }

  return { encryptAES, decryptAES, decryptData }
}
