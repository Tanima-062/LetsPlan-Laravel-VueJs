import axios from "../axios";

export default {
    data() {
        return {
            nationalities: [],
            selected_nationality: null,
            selected_nationality_id: null,
        }
    },
    methods: {
        getNationalities() {
            axios.get('/api/nationalities').then(res => {
                this.nationalities = res.data.data
            })
        },
        getSelectedNationality() {
            if (this.selected_nationality.length) {
                const lang = this.nationalities.find(item => {
                    return item.name === this.selected_nationality
                })
                this.selected_nationality_id = lang.id
            }
        },
    }
}
