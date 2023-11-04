<template>
    <div class="card-panel-home table-card bg-white">
        <div v-for="(person,index) in anmeldungens" class="records-for-all" :key="index" :class="toMili(person.created_at) + (3600*1000) > toMili(new Date()) ? 'registered-child' : 'registered-child-old'">
            <div class="child-record">
                <div class="photo_name">
                    <div v-colors class="child-photo">
                        <p class="child-initials">{{person.child_first_name.charAt(0).toUpperCase()}}{{person.child_last_name.charAt(0).toUpperCase()}}</p>
                    </div>
                    <p class="child-info child-name">{{person.child_first_name}} {{person.child_last_name}}</p>
                </div>
                <div class="child-heutig">
                    <p class="child-info">
                        <span>{{person.spielgruppen}}</span>
                    </p>
                </div>
                <div class="child-playgroup">
                    <p class="child-info child-playgroup">{{ person.semester_id }} x wöchentlich</p>
                </div>
                <p class="natinality">{{ person.mother_tongue_name }}</p>
                <p class="child-id">Anmeldung #{{person.id}}</p>
               <div class="actions">
                   <button v-if="toMili(person.created_at)  + (3600*1000) > toMili(new Date())" class="btn btn-danger">Neu</button>
                   <img @click="$router.push(`/anmeldungen/${person.id}`)" class="eye-icon" src="/images/eye.png"/>
               </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    name: "AnmeldungenTable",
    props: ['anmeldungens'],
    data() {
        return {}
    },
    directives: {
        colors: {
            inserted(el) {
                el.style.backgroundColor = `#${(
                    Math.floor(Math.random() * 16777215)
                ).toString(16)}`;
            }
        }
    },

    methods: {
        toMili(date) {
           return moment(date).valueOf()
        }
    }
}
</script>

<style scoped lang="scss">

.table-card {
    margin-top: 25px;
    position: relative;
    max-height:236px;
    overflow-y: auto;
    height: 100%;
}

.child-photo {
    width: 30px;
    height: 30px;
    margin-right: 15px;
    border-radius: 50%;
    .child-initials{
        font-weight: 400;
        font-size: 13px;
        width: 30px;
        height: 30px;
        color: #1B343D;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}


.child-record {
    display: flex;
    align-items: center;
    width: 100%;
    justify-content: space-between;
    padding-top: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid #C2C5C6;
   & > * {
        flex: 1 1 0px !important;
    }
}

.registered-child {
    background: rgba(0, 99, 167,0.1);
}

.registered-child-old {
    background-color: white;

}
.registered-child,
.registered-child-old{
    padding: 0px 20px;
}
.registered-child:first-child {
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
.records-for-all:last-child .child-record {
   border-bottom: 0;
}
.photo_name {
    display: flex;
    flex-basis: 145px !important;
    align-items: center;
}
.child-heutig {
    flex-basis: 130px !important;
}
.child-playgroup {
    flex-basis: 81px !important;
}
.natinality {
    flex-basis: 85px !important;
}
.child-id {
    flex-basis: 105px !important;
}
.actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-basis: 50px !important;
}
.btn-danger {
    padding: 0px 7px;
    background: #E62D4F;
    border-radius: 3px;
    font-family: 'Lato';
    font-weight: 400;
    font-size: 10px;
    line-height: 18px;
    color: #FFFFFF;
    margin-right: 15px;
}

.eye-icon {
    width: 19px;
    height: 10px;
    cursor: pointer;
}

.form-input-bordered{
    background: #F8FBFA;
    border: 0.5px solid #727574;
    border-radius: 5px;
}
.child-name,
.child-info span,
p{
    font-weight: 500;
    font-size: 12px;
    line-height: 14px;
    color: #727574;
}
.child-name {
    font-weight: 600;
    color: #42474A;
}
</style>
