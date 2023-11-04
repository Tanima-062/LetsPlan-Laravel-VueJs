import axios from "../axios";

export default {
    data () {
        return {
            languages:[],
            selected_language: null,
            selected_language_id: null,
        }
    },
    methods: {
        getLanguages() {
            axios.get('/api/languages').then(res=>{
                this.languages = res.data.data
            })
        },
        getSelectedLanguage() {
            if(this.selected_language.length) {
                const lang = this.languages.find(item=>{
                    return item.name === this.selected_language
                })
                this.selected_language_id = lang.id
            }
        },
    }
}
