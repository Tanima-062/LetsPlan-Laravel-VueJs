<template>
    <div>
        <div class="flex" v-if="filepath=='' && src==''">
            <div class="upload-image">
                <input
                    id="inpFile"
                    hidden
                    @change="imageUpload"
                    type="file"
                    accept="image/*"
                />
                <div
                    @click="openImageSelection"
                    class="image-preview"
                    id="image-preview"
                >
                    <svg
                        width="34"
                        height="32"
                        viewBox="0 0 34 32"
                        xmlns="http://www.w3.org/2000/svg"
                        id="hide"
                        class="mt-2"
                    >
                        <path
                            d="M25.3333 11.6667H24.0833V12.9167H25.3333V11.6667ZM28.0408 23.0847L26.9588 23.7106L28.2106 25.8746L29.2926 25.2487L28.0408 23.0847ZM10.3463 13.1818L11.5348 13.569L12.3091 11.1919L11.1206 10.8047L10.3463 13.1818ZM4.70743 25.2487L5.78943 25.8746L7.04125 23.7106L5.95924 23.0847L4.70743 25.2487ZM17 15L17.8839 14.1161L17 13.2323L16.1161 14.1161L17 15ZM15.75 30V31.25H18.25V30H15.75ZM21.1161 20.8839L22 21.7678L23.7678 20L22.8839 19.1161L21.1161 20.8839ZM11.1161 19.1161L10.2322 20L12 21.7678L12.8839 20.8839L11.1161 19.1161ZM9.91667 10C9.91667 6.088 13.088 2.91669 17 2.91669V0.416687C11.7073 0.416687 7.41667 4.70729 7.41667 10H9.91667ZM17 2.91669C20.912 2.91669 24.0833 6.088 24.0833 10H26.5833C26.5833 4.70729 22.2927 0.416687 17 0.416687V2.91669ZM24.0833 10V11.6667H26.5833V10H24.0833ZM7.41667 10V11.6667H9.91667V10H7.41667ZM25.3333 12.9167C28.3249 12.9167 30.75 15.3418 30.75 18.3334H33.25C33.25 13.9611 29.7056 10.4167 25.3333 10.4167V12.9167ZM30.75 18.3334C30.75 20.3489 29.6523 22.1525 28.0408 23.0847L29.2926 25.2487C31.6664 23.8755 33.25 21.2531 33.25 18.3334H30.75ZM3.25 18.3334C3.25 15.3418 5.67512 12.9167 8.66667 12.9167V10.4167C4.29441 10.4167 0.75 13.9611 0.75 18.3334H3.25ZM8.66667 12.9167C9.25499 12.9167 9.81905 13.01 10.3463 13.1818L11.1206 10.8047C10.3464 10.5525 9.52118 10.4167 8.66667 10.4167V12.9167ZM5.95924 23.0847C4.34769 22.1525 3.25 20.3489 3.25 18.3334H0.75C0.75 21.2531 2.33363 23.8755 4.70743 25.2487L5.95924 23.0847ZM15.75 15V30H18.25V15H15.75ZM22.8839 19.1161L17.8839 14.1161L16.1161 15.8839L21.1161 20.8839L22.8839 19.1161ZM16.1161 14.1161L11.1161 19.1161L12.8839 20.8839L17.8839 15.8839L16.1161 14.1161Z"
                            fill="white"
                        />
                    </svg>
                    <img
                        :src="src"
                        v-show="image"
                        alt="Image Preview"
                        class="image-preview__image"
                        id="image-preview__image"
                    />
                    <span
                        class="image-preview__defailt-text text-xs mb-2"
                        id="image-preview__defailt-text"
                        style="color: white; font-family: Nexa; margin-top: 15px;"
                        >Bild hochladen</span
                    >
                </div>
            </div>
            <span><div class="title">Bild</div></span>
        </div>
        <div class="flex" v-else>
            <div class="upload-image-2">
                <input id="inpFile" hidden @change="imageUpload" type="file" accept="image/*"/>
                <div class="image-preview" id="image-preview">
                    <img :src="src" class="image-preview__image"/>
                </div>
            </div>
            <span>
                <div class="title">Bild</div>
                <div class="mt-2 mb-2" @click="openImageSelection"><UploadIcon class="mr-2"/>Bild ändern</div>
                <div @click="removeFile"><DeleteIcon class="mr-2"/>Bild entfernen</div>
            </span>
        </div>
    </div>
</template>

<script>
import UploadIcon from "../components/spielgruppen/components/UploadIcon.vue";
import DeleteIcon from "../components/spielgruppen/components/DeleteIcon.vue";
export default {
    name: "FileUploadEdit",
    props:{
        error:{
            default:''
        },
        image:{
            default:''
        },
        filepath:{
            default: ''
        }
    },
    components: {
        UploadIcon,
        DeleteIcon
    },
    data:()=>({
        src: '',
     }),
    created() {
        this.src = this.filepath
    },
    mounted() {
        setTimeout(()=>{
                if (this.filepath){
                    const previewContainer = document.getElementById('image-preview')
                    const previewImage = previewContainer.querySelector('.image-preview__image')
                    previewImage.setAttribute("src", this.filepath)
                }

        },1000)
    },
    methods:{
        openImageSelection() {
            document.getElementById('inpFile').click();
        },
        removeFile(){
            this.src='';
            const previewContainer = document.getElementById('image-preview');
            const previewImage = previewContainer.querySelector('.image-preview__image');
            previewImage.setAttribute("src", "");
            document.getElementById('inpFile').value = '';
            this.$emit('handleImageUpload', null)
        },
        imageUpload(event){

            const previewContainer = document.getElementById('image-preview')
            const previewImage = previewContainer.querySelector('.image-preview__image')

            this.src = event.target.files[0]

            if (this.src) {
                const reader = new FileReader();
                reader.addEventListener('load', function () {
                    previewImage.setAttribute("src", this.result)
                });
                reader.readAsDataURL(this.src)
            } else {
                previewImage.setAttribute("src", "")
            }
            this.$emit('handleImageUpload',event.target.files[0])
        }
    }
}
</script>

<style scoped lang="scss">
    .title{
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 21px;
        color: #16A9B7;
    }
.upload-image-2 {
    margin-right: 15px;
    margin-bottom: 15px;

    .image-preview {
        width: 104px;
        height: 104px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        background-color: #d2dce2;
        position: relative;
        border-radius: 5px;
        &__image {
            width: 104px;
            height: 104px;
            object-fit: cover;
            border-radius: 5px;
        }
    }
}
.upload-image {
    margin-right: 15px;
    margin-bottom: 15px;

    .image-preview {
        width: 104px;
        height: 104px;
        display: flex;
        // flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        background-color: #063f5f;
        position: relative;
        border-radius: 5px;
        flex-direction: column;
        &:hover {
            cursor: pointer;
            background: rgba(226, 226, 226, 0.2);
            transition: 100ms all ease-in;
            svg {
                path {
                    fill: #063f5f;
                }
            }
            span {
                color: #063f5f !important;
            }
        }
        &__image {
            width: 104px;
            height: 104px;
            object-fit: cover;
            border-radius: 5px;
            position: absolute;
            top: 0;
            left: 0;
        }
        .photo-selection {
            background: #1b343d;
            opacity: 0.75;
            width: 100%;
            padding-block: 7px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            bottom: 0;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
    }
}

</style>
