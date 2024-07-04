<template>
    <div class="qw_input">
        <q-font v-if="params.type == 'icon'" v-model:value="data.modelFormValue" />
        <table-select v-if="params.type == 'table_select'" v-model:value="data.modelFormValue" :params="params"
            @changeVla="cascaderChange" />
        <el-input v-if="params.type == 'text' || params.type == 'password' || params.type == 'number' || params.type == undefined"
            :type="params.type || 'text'" v-model="data.modelFormValue" :placeholder="params.placeholder || ''"
            :disabled="params.disabled || false" />
        <el-input v-if="params.type == 'textarea'" :type="'textarea'" class="input_textarea"
            :show-word-limit="params.showWordLimit || true" :maxlength="params.maxlength || 15" v-model="data.modelFormValue"
            :placeholder="params.placeholder || ''" :disabled="params.disabled || false" />
        <el-cascader @change="cascaderChange" style="width:100%" v-if="params.type == 'cascader'"
            v-model="data.modelFormValue" :options="dictData[params.value] || []" :props="params.props || {}"
            :placeholder="params.placeholder || ''" />
        <el-cascader @change="cascaderChange" style="width:100%" v-if="params.type == 'cascader_lazy'"
            v-model="data.modelFormValue" :options="dictData[params.value] || []"
            :props="params.props || { emitPath: true, label: 'name', value: 'id', lazy: true, lazyLoad: lazyLoad }"
            :placeholder="params.placeholder || ''" />
        <el-select style="width:100%" v-if="params.type == 'select'" v-model="data.modelFormValue"
            :filterable="params.filterable || false" :multiple="params.multiple || false" :placeholder="params.placeholder || ''">
            <el-option v-for="(v, k) in (dictData[params.value] || [])" :key="k"
                :label="params.oneArray ? v : (v[(params.labelName || 'label')])"
                :value="params.oneArray ? v : v[(params.valueName || 'value')]" />
        </el-select>
        <el-autocomplete v-if="params.type == 'autocomplete'" v-model="data.modelFormValue" @select="cascaderChange"
            :fetch-suggestions="querySearch">
            <template #default="{ item }">
                <div class="value">{{ item }}</div>
            </template>
        </el-autocomplete>
        <el-upload v-if="params.type == 'avatar' || params.type == 'image'"
            :class="params.type == 'avatar' ? 'avatar-uploader' : 'avatar-uploader images'"
            :action="'/api' + uploadPath + 'uploads'" :show-file-list="false" :headers="{ Authorization: Token }"
            :data="{ option: params.option || null, name: params.value || null }" :on-success="handleAvatarSuccess">
            <img v-if="data.modelFormValue" style="width:100%;height:100%" :src="data.modelFormValue"
                :class="params.type == 'avatar' ? 'avatar' : 'avatar images'" />
            <el-icon v-else class="avatar-uploader-icon">
                <plus />
            </el-icon>
        </el-upload>
        <el-upload v-if="params.type == 'file'" :class="'avatar-uploader file'" :action="'/api' + uploadPath + 'uploads'"
            :show-file-list="false" :headers="{ Authorization: Token }"
            :data="{ option: params.option || null, name: params.value || null, uploadType: 'uploadFile' }"
            :on-success="handleAvatarSuccess">
            <span v-if="data.modelFormValue" :title="data.modelFormValue" class="file_path">{{ data.modelFormValue }}</span>
            <el-icon v-else class="avatar-uploader-icon file" :size="20">
                <Upload />
            </el-icon>
        </el-upload>
        <!-- editer -->
        <div class="editor" v-if="params.type == 'editor'" :style="'height:' + (params.height || '385px')">
            <div :id="'toolbar' + rand"></div>
            <div class="editClass" :style="'height:' + (params.height || '300px')" :id="'editor' + rand"></div>
        </div>
        <el-switch v-model="data.modelFormValue" v-if="params.type == 'switch'"
            :active-text="params.activeText || $t('btn.yes')" :inactive-text="params.inactiveText || $t('btn.no')"
            :inline-prompt="params.inlinePrompt || true" />
        <el-radio-group v-model="data.modelFormValue" v-if="params.type == 'radio'">
            <el-radio v-for="(v, k) in (dictData[params.value] || [])" :key="k"
                :label="params.oneArray ? v : (v[(params.labelName || 'value')])">{{ params.oneArray ? v : (v[(params.valueName || 'label')]) }}</el-radio>
        </el-radio-group>
        <el-date-picker v-if="params.type == 'datetime'" style="width:100%" type="datetime" value-format="YYYY-MM-DD HH:mm:ss"
            :placeholder="params.placeholder || ''" v-model="data.modelFormValue" />
        <el-date-picker v-if="params.type == 'daterange'" style="width:100%;box-sizing: border-box;" type="daterange"
            value-format="YYYY-MM-DD HH:mm:ss" :start-placeholder="params.startPlaceholder || $t('btn.dateRangeStart')"
            :end-placeholder="params.endPlaceholder || $t('btn.dateRangeEnd')" v-model="data.modelFormValue" />
        <el-date-picker v-if="params.type == 'date'" style="width:100%" type="date" value-format="YYYY-MM-DD"
            :placeholder="params.placeholder || ''" v-model="data.modelFormValue" />
    </div>
</template>

<script>
import { ref, reactive, watch, onMounted, nextTick, getCurrentInstance } from "vue"
import qFont from './font'
import tableSelect from './table_select'
import { Plus, Upload } from '@element-plus/icons'
import { getToken, getUploadPath, editSplitStr, editorHandle } from '@/plugins/config'
export default {
    components: { qFont, tableSelect, Plus, Upload },
    props: ['params', 'formData', 'dictData'],
    setup(props, { emit }) {
        const { proxy } = getCurrentInstance()
        const rand = ref((Math.random().toFixed(8)).replace('0.', ''))
        const data = reactive({
            modelFormValue: null
        })
        const objects = reactive({
            editorObj: null,
            E: null,
        })
        const splitStr = editSplitStr
        data.modelFormValue = props.formData
        watch(() => props.formData, (e) => {
            data.modelFormValue = e
            emit('update:formData', e)
            if (props.params.type == 'editor') {
                if (objects.editorObj !== null && objects.E !== null && editorHandle(e) != '') {
                    if (objects.editorObj.getHtml() != editorHandle(e)) {
                        objects.E.SlateTransforms.removeNodes(objects.editorObj, { at: [0] })
                        objects.editorObj.insertNode(editorHandle(e, 1))
                    }
                }
            }
        }, { deep: true })
        watch(() => data.modelFormValue, (e) => {
            if (e != undefined) emit('update:formData', e)
        }, { deep: true })

        // cascader 第一次无法显示rule验证问题
        const cascaderChange = (e) => {
            emit('update:formData', e)
        }

        // 懒加载
        const lazyLoad = async (node, resolve) => {
            if (node.loaded) return
            if (node.level == 0) {
                return resolve(await proxy.R.get(props.params.lazyUrl + '&pid=0'))
            } else {
                const data = await proxy.R.get(props.params.lazyUrl + '&pid=' + node.data[props.params.idName || 'code'])
                resolve(data)
            }
        }

        if (props.params.type == 'cascader_lazy' && props.params.props && props.params.props.lazy) {
            props.params.props.lazyLoad = lazyload
        }

        // 自动补全
        const querySearch = (queryString, cb) => {
            let data = props.dictData[props.params.value].filter((e) => {
                return e.search(queryString) != -1
            })
            cb(data)
        }

        onMounted(() => {
            if (props.params.type == 'editor') {
                try {
                    getEditDom()
                } catch (e) {
                    console.error(e)
                }

            }
        })


        const getEditDom = async () => {
            const { ctx, proxy } = getCurrentInstance()
            nextTick(() => {
                // document.querySelector('#editor'+rand.value)
                // 编辑器初始化
                let editorConfig = { MENU_CONF: {} }
                editorConfig.autoFocus = true
                editorConfig.placeholder = props.params.placeholder || proxy.$t('btn.inputContent')
                editorConfig.onChange = (editor) => {
                    // 当编辑器选区、内容变化时，即触发
                    // console.log('content', editor.children)
                    // console.log('html', editor.getHtml())
                    emit('update:formData', editor.getHtml() + splitStr + JSON.stringify(editor.children))
                }
                editorConfig.MENU_CONF['uploadImage'] = {
                    server: '/api' + uploadPath + 'uploads',
                    // form-data fieldName ，默认值 'wangeditor-uploaded-file'
                    fieldName: 'file',
                    maxFileSize: 10 * 1024 * 1024, // 1M
                    maxNumberOfFiles: 10,
                    // 选择文件时的类型限制，默认为 ['image/*'] 。如不想限制，则设置为 []
                    allowedFileTypes: ['image/*'],
                    meta: {
                        name: 'editor',
                    },
                    // 自定义增加 http  header
                    headers: {
                        Authorization: Token,
                    },
                    // 跨域是否传递 cookie ，默认为 false
                    withCredentials: true,
                    timeout: 5 * 1000, // 5 秒
                    // 小于该值就插入 base64 格式（而不上传），默认为 0
                    base64LimitKB: 5, // 5kb
                    customInsert(res, insertFn) {
                        if (res.code != 200) return ElementPlus.ElMessage.error(res.msg)
                        // 从 res 中找到 url alt href ，然后插图图片
                        insertFn(res.data, res.data, res.data)
                    },
                }
                var E = window.wangEditor; // 全局变量
                const editorObj = E.createEditor({
                    selector: '#editor' + rand.value,
                    config: editorConfig,
                    mode: 'default' // 或者 'simple' ，下文有解释
                })
                objects.editorObj = editorObj
                objects.E = E
                if (!proxy.R.isEmpty(props.formData)) {
                    let splitContent = props.formData.split(splitStr)
                    if (splitContent.length == 2) {
                        E.SlateTransforms.removeNodes(editorObj, { at: [0] })
                        editorObj.insertNode(JSON.parse(splitContent[1]))
                    }
                }


                // 创建工具栏
                E.createToolbar({
                    editor: editorObj,
                    selector: '#toolbar' + rand.value,
                    mode: 'simple' // 或者 'simple' ，下文有解释
                })
            })


        }

        const getEditObjec = () => {
            return objects
        }


        // 头像上传
        const handleAvatarSuccess = (e) => {
            if (e.code != 200) return ElementPlus.ElMessage.error(e.msg)
            emit('update:formData', e.data)
        }

        const Token = getToken()
        const uploadPath = getUploadPath()
        return { rand, cascaderChange, lazyLoad, getEditObjec, querySearch, handleAvatarSuccess, Token, uploadPath, data }
    }
}
</script>

<style lang="scss">
.qw_input {
    width: 100%;

    .editor {
        border: 1px solid #efefef;

        h1 {
            font-size: 2em;
        }

        h2 {
            font-size: 1.5em;
        }

        h3 {
            font-size: 1.17em;
        }
    }

    .avatar-uploader {
        &.images .el-upload {
            width: 100%;
            height: auto;
            max-height: 200px;
        }

        &.file .el-upload {
            width: 100%;
            height: 40px;
            line-height: 16px;
            font-size: 12px;
            text-align: left;
            overflow: inherit;

        }

        .file_path {
            width: 80%;
            padding: 5px;
            overflow: hidden;
            display: block;
        }
    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        width: 78px;
        height: 78px;

    }

    .avatar-uploader .el-upload:hover {
        border-color: #409eff;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 78px;
        height: 78px;
        text-align: center;

        &.file {
            width: 100%;
            height: 40px;
        }
    }

    .avatar-uploader-icon svg {
        // margin-top: 26px; /* (178px - 28px) / 2 - 1px */
    }

    .avatar {
        &.images {
            width: 100%;
            height: auto;
            max-height: 200px;
        }

        width: 178px;
        height: 178px;
        display: block;
    }

    .editClass {
        border-top: 1px solid #efefef;
        height: 100%;
        width: 100%;
    }

    .input_textarea {
        width: 100%;
    }
}</style>