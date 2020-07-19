import admin from '@/plugins/apis/admin'
import seller from '@/plugins/apis/seller'
import home from '@/plugins/apis/home'
export const api = {
    ...admin,
    ...seller,
    ...home,
}