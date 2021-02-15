<template>
    <div class="container">
        <!--        <div class="row">-->
        <!--            <div class="col-sm-3">-->
        <!--                <b-form-input v-model="variable" placeholder="Enter your name" type="number"></b-form-input>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="row mt-3">
            <div class="col-sm-3">
                <label for="material">Материал</label>
                <b-form-select id="material" v-model="material" :options="materialData" size="sm"
                               @change="onSetMaterial" value-field="id" text-field="name"></b-form-select>
            </div>
        </div>
        <div v-if="materialSelected">
            <div class="row mt-3">
                <div class="col-sm-3">
                    <label for="thickness">Толщина</label>
                    <b-form-select id="thickness" v-model="thickness" :options="thicknessOptions" size="sm"
                                   @change="onSetThickness" value-field="id" text-field="thickness"></b-form-select>
                </div>
                <div v-if="thicknessSelected" class="col-sm-3">
                    <label for="list-length">Длина листа</label>
                    <b-form-select id="list-length" v-model="listDimensionId" :options="dimensionsOptions" size="sm"
                                   value-field="id" text-field="length"></b-form-select>
                </div>
                <div v-if="thicknessSelected" class="col-sm-3">
                    <label for="list-width">Ширина листа</label>
                    <b-form-select id="list-width" v-model="listDimensionId" :options="dimensionsOptions" size="sm"
                                   value-field="id" text-field="width"></b-form-select>
                </div>
            </div>
        </div>
        <div v-if="materialSelected">
            <div class="row mt-3">
                <div class="col-sm-6 text-center">
                    <span class="h5">Размеры отрезка</span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3">
                    <b-form-input id="length" type="number" v-model="dimension1" size="sm"></b-form-input>
                </div> *
                <div class="col-sm-3">
                    <b-form-input id="width" type="number" v-model="dimension2" size="sm"></b-form-input>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <div class="alert-success font-weight-bold">{{ info }}</div>
                <div class="alert-success font-weight-bold">{{ formulaText }} = {{ result }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Calculator.vue",
    props: ['materialData'],
    methods: {
        onSetMaterial: function (material) {
            let info = this.materialData.find(function (element) {
                return element.id === material;
            });

            this.materialInfo = info;
            this.thicknessOptions = info.thicknesses;
            this.materialSelected = true;
            this.thickness = info.thicknesses[0].id;
            this.onSetThickness(info.thicknesses[0].id);
        },onSetThickness: function (thickness) {
            let thicknessInfo = this.materialInfo.thicknesses.find(function (element) {
                return element.id === thickness;
            });

            this.thicknessInfo = thicknessInfo;

            this.dimensionsOptions = thicknessInfo.dimensions;
            this.listDimensionId = thicknessInfo.dimensions[0].id;
            this.thicknessSelected = true;
        },
        onSetListLength: function (lengthId) {
            this.listWidth = lengthId;
        },
        onSetListWidth: function (widthId) {
            this.listLength = widthId;
        },
        clearResult: function (widthId) {
            this.info = '';
            this.formulaText = '';
        }
    },
    data: function () {
        return {
            variable: "",
            material: "",
            thickness: "",
            listDimensionId: "",
            dimension1: 0,
            dimension2: 0,
            materialSelected: false,
            thicknessSelected: false,
            thicknessOptions: [],
            dimensionsOptions: [],
            materialInfo: {},
            thicknessInfo: {},
            usdCourse: 29,
            formulaText: '',
            info: '',
        }
    },
    mounted: function () {
        this.material = this.materialData[0].id;
        this.onSetMaterial(this.materialData[0].id);
    },
    computed: {
        result: function () {
            if (this.dimensionsOptions.length === 0) {
                this.clearResult();
                return 'undefined';
            }
            let thickness = this.thicknessInfo.thickness;
            let density = parseFloat(this.materialInfo.density);
            let usdCourse = parseFloat(this.usdCourse);
            let listDimensionId = this.listDimensionId;

            let dimensionInfo = this.dimensionsOptions.find(function (element) {
                return element.id === listDimensionId;
            });

            let listWidth = parseFloat(dimensionInfo.width);
            let listLength = parseFloat(dimensionInfo.length);
            let price = parseFloat(dimensionInfo.price);

            let width = parseFloat(this.dimension1);
            let length = parseFloat(this.dimension2);

            if (width === undefined || width <= 0 || length === undefined || length <= 0) {
                this.clearResult();
                return 'error';
            }

            if (width < length) {
                let temp = length;
                length = width;
                width = temp;
            }

            if (width > listWidth) {
                if (width > listLength || length > listWidth) {
                    this.clearResult();
                    return 'неправильные размеры листа'
                }
                let temp = length;
                length = width;
                width = temp;
            }

            let res;

            this.info = "размер листа - " + length + "*" + width;
            if (width >= 0.85 * listWidth) {
                this.formulaText = thickness + " * " + density + " * " + (length + 3) + " * " + listWidth + " * " + 1.1 + ' / ' + 1000000
                    + ' * ' + price + " * " + usdCourse + " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (length + 3) * listWidth * 1.1 / 1000000 * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13

            } else if (length >= 0.85 * listLength) {
                this.formulaText = thickness + " * " + density + " * " + (width + 3) + " * " + listLength + " * " + 1.2 + ' / ' + 1000000
                    + ' * ' + price + " * " + usdCourse + " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (width + 3) * listLength * 1.2 / 1000000 * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13

            } else {
                this.formulaText = thickness + " * " + density + " * " + (width + 3) + " * " + (length + 3) + ' / ' + 1000000
                    + ' * ' + 1.331 + ' * ' + price + " * " + usdCourse +
                    " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (width + 3) * (length + 3) / 1000000 * 1.331 * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13
            }

            return Math.round(res*100)/100;
        }
    },
}
</script>
