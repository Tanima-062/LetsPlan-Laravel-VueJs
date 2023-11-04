export default {
    methods: {
         phoneNumberFormater (phoneNumber) {
             if(phoneNumber.length > 12) {
                 return phoneNumber = phoneNumber.slice(0, 12)
             }
             return phoneNumber
        }
    }
}
