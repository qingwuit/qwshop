import admin from '@/plugins/apis/admin'
import seller from '@/plugins/apis/seller'
export const api = {
    ...admin,
    ...seller,
}