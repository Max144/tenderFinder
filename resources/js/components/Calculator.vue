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
                <b-form-select id="material" v-model="material" :options="materialOptions" size="sm"
                               @change="onSetMaterial"></b-form-select>
            </div>
        </div>
        <div v-if="materialSelected">
            <div class="row mt-3">
                <div class="col-sm-3">
                    <label for="thickness">Толщина</label>
                    <b-form-select id="thickness" v-model="thickness" :options="thicknessOptions"
                                   size="sm"></b-form-select>
                </div>
                <div class="col-sm-3">
                    <label for="list-length">Длина листа</label>
                    <b-form-select id="list-length" v-model="listLength" :options="lengthOptions" size="sm"
                                   @change="onSetListLength"></b-form-select>
                </div>
                <div class="col-sm-3">
                    <label for="list-width">Ширина листа</label>
                    <b-form-select id="list-width" v-model="listWidth" :options="widthOptions" size="sm"
                                   @change="onSetListWidth"></b-form-select>
                </div>
            </div>
        </div>
        <div v-if="materialSelected">
            <div class="row mt-3">
                <div class="col-sm-3">
                    <label for="length">Длина отрезка</label>
                    <b-form-input id="length" type="number" v-model="length" size="sm"></b-form-input>
                </div>
                <div class="col-sm-3">
                    <label for="width">Ширина отрезка</label>
                    <b-form-input id="width" type="number" v-model="width"  size="sm"></b-form-input>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <span class="alert-success font-weight-bold">{{formulaText}} = {{ result }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Calculator.vue",
    methods: {
        onSetMaterial: function (material) {
            let info = this.materialOptions.find(function (element) {
                return element.value === material;
            });

            this.materialInfo = info;

            if (info !== undefined) {
                this.materialSelected = true;
                this.thicknessOptions = info.ths.map(function (data) {
                    return {text: data.value, value: data.value, price: data.price};
                });
                this.thickness = info.ths[0].value;

                this.lengthOptions = info.dimensions.map(function (data) {
                    return {text: data.length, value: data.value};
                });
                this.listLength = this.lengthOptions[0].value;

                this.widthOptions = info.dimensions.map(function (data) {
                    return {text: data.width, value: data.value};
                });
                this.listWidth = this.widthOptions[0].value;

            } else {
                this.materialSelected = false;
            }
        },
        onSetListLength: function (lengthId) {
            this.listWidth = lengthId;
        },
        onSetListWidth: function (widthId) {
            this.listLength = widthId;
        }
    },
    data: function () {
        return {
            variable: "",
            material: "",
            thickness: "",
            listLength: "",
            listWidth: "",
            length: 0,
            width: 0,
            materialSelected: false,
            thicknessOptions: [],
            lengthOptions: [],
            widthOptions: [],
            materialInfo: {},
            usdCourse: 29,
            formulaText: '',
            materialOptions: [
                {
                    value: 0,
                    text: 'Оргстекло',
                    ths: [
                        {value: 1, price: 5},
                        {value: 2, price: 5},
                        {value: 3, price: 3.63},
                        {value: 5, price: 3.63},
                        {value: 6, price: 3.63},
                        {value: 8, price: 3.63},
                        {value: 10, price: 3.63},
                    ],
                    dimensions: [
                        {value: 0, length: 1850, width: 1250},
                        {value: 1, length: 3050, width: 2050},
                    ],
                    density: 1.2,
                },
                {
                    value: 1,
                    text: 'Полипропилен серый',
                    ths: [
                        {value: 2, price: 3.07},
                        {value: 3, price: 2.3},
                        {value: 4, price: 2.3},
                        {value: 5, price: 2.3},
                        {value: 6, price: 2.3},
                        {value: 8, price: 2.3},
                        {value: 10, price: 2.3},
                        {value: 12, price: 2.3},
                        {value: 15, price: 2.3},
                        {value: 20, price: 2.3},
                        {value: 25, price: 2.3},
                        {value: 30, price: 2.3},
                        {value: 40, price: 2.3},
                    ],
                    dimensions: [
                        {value: 0, length: 3000, width: 1500},
                    ],
                    density: 0.98
                },
                {
                    value: 2,
                    text: 'Полипропилен белый',
                    ths: [
                        {value: 3, price: 3.9},
                        {value: 4, price: 3.9},
                        {value: 5, price: 3.9},
                        {value: 6, price: 3.9},
                        {value: 8, price: 3.9},
                        {value: 10, price: 3.9},
                        {value: 12, price: 3.9},
                        {value: 15, price: 3.9},
                        {value: 20, price: 3.9},
                        {value: 25, price: 3.9},
                        {value: 30, price: 3.9},
                        {value: 40, price: 3.9}
                    ],
                    dimensions: [
                        {value: 0, length: 2000, width: 1000},
                    ],
                    density: 0.96
                },
                {
                    value: 3,
                    text: 'ПЕ500',
                    ths: [
                        {value: 1, price: 3.9},
                        {value: 2, price: 3.9},
                        {value: 3, price: 3.9},
                        {value: 4, price: 3.9},
                        {value: 5, price: 3.9},
                        {value: 6, price: 3.9},
                        {value: 8, price: 3.9},
                        {value: 10, price: 3.9},
                        {value: 12, price: 3.9},
                        {value: 15, price: 3.9},
                        {value: 20, price: 3.9},
                        {value: 25, price: 3.9},
                        {value: 30, price: 3.9},
                        {value: 40, price: 3.9}
                    ],
                    dimensions: [
                        {value: 0, length: 2000, width: 1000},
                    ],
                    density: 0.96
                },
                {
                    value: 4,
                    text: 'Полиэтилен 1000',
                    ths: [
                        {value: 1, price: 7},
                        {value: 2, price: 7},
                        {value: 3, price: 7},
                        {value: 4, price: 7},
                        {value: 5, price: 7},
                        {value: 6, price: 7},
                        {value: 8, price: 7},
                        {value: 10, price: 7},
                        {value: 12, price: 7},
                        {value: 15, price: 7},
                        {value: 20, price: 7},
                        {value: 25, price: 7},
                        {value: 30, price: 7},
                        {value: 40, price: 7}
                    ],
                    dimensions: [
                        {value: 0, length: 2000, width: 1000},
                    ],
                    density: 0.96
                },
                {
                    value: 5,
                    text: 'Текстолит 2c',
                    ths: [
                        {value: 0.5, price: 5.5},
                        {value: 1, price: 4.5},
                        {value: 1.5, price: 4.5},
                        {value: 2, price: 4},
                        {value: 2.5, price: 4},
                        {value: 3, price: 2.9},
                        {value: 4, price: 2.9},
                        {value: 5, price: 2.9},
                        {value: 6, price: 2.9},
                        {value: 8, price: 2.9},
                        {value: 10, price: 2.9},
                        {value: 12, price: 2.9},
                        {value: 15, price: 2.9},
                        {value: 20, price: 2.9},
                        {value: 25, price: 2.9},
                        {value: 30, price: 2.9},
                        {value: 40, price: 2.9},
                        {value: 50, price: 2.9},
                        {value: 60, price: 3.6},
                        {value: 70, price: 3.6},
                        {value: 80, price: 3.6},
                        {value: 90, price: 3.6},
                        {value: 100, price: 3.6},
                        {value: 145, price: 7},
                        {value: 175, price: 7},
                    ],
                    dimensions: [
                        {value: 0, length: 2020, width: 1020},
                    ],
                    density: 1.6
                },
                {
                    value: 6,
                    text: 'Стеклотекстолит 2c',
                    ths: [
                        {value: 0.2, price: 7.5},
                        {value: 0.3, price: 7.5},
                        {value: 0.4, price: 7.5},
                        {value: 0.5, price: 3.8},
                        {value: 0.6, price: 3.8},
                        {value: 0.8, price: 3.8},
                        {value: 1, price: 3.6},
                        {value: 1.5, price: 3.6},
                        {value: 2, price: 3.2},
                        {value: 2.5, price: 3.2},
                        {value: 3, price: 2.9},
                        {value: 4, price: 2.9},
                        {value: 5, price: 2.9},
                        {value: 6, price: 2.9},
                        {value: 8, price: 2.9},
                        {value: 10, price: 2.9},
                        {value: 12, price: 2.9},
                        {value: 15, price: 2.9},
                        {value: 20, price: 2.9},
                        {value: 25, price: 2.9},
                        {value: 30, price: 2.9},
                        {value: 40, price: 2.9},
                        {value: 50, price: 2.9},
                        {value: 60, price: 3.5},
                    ],
                    dimensions: [
                        {value: 0, length: 2020, width: 1020},
                    ],
                    density: 2.136
                },
                {
                    value: 7,
                    text: 'Капролон',
                    ths: [
                        {value: 5, price: 5.5},
                        {value: 6, price: 5.5},
                        {value: 8, price: 5},
                        {value: 10, price: 5},
                        {value: 12, price: 5},
                        {value: 15, price: 5},
                        {value: 20, price: 5},
                        {value: 25, price: 5},
                        {value: 30, price: 5},
                        {value: 40, price: 5},
                        {value: 50, price: 5},
                        {value: 60, price: 5},
                        {value: 70, price: 5},
                        {value: 80, price: 5},
                        {value: 100, price: 5},
                    ],
                    dimensions: [
                        {value: 0, length: 2100, width: 1050},
                    ],
                    density: 1.315
                },
                {
                    value: 8,
                    text: 'POM',
                    ths: [
                        {value: 8, price: 5},
                        {value: 10, price: 5},
                        {value: 15, price: 5},
                        {value: 20, price: 5},
                        {value: 25, price: 5},
                        {value: 30, price: 5},
                        {value: 40, price: 5},
                        {value: 50, price: 5},
                        {value: 60, price: 5},
                        {value: 80, price: 5},
                        {value: 100, price: 5},
                    ],
                    dimensions: [
                        {value: 0, length: 2000, width: 1000},
                    ],
                    density: 1.483
                },
                {
                    value: 9,
                    text: 'PU',
                    ths: [
                        {value: 3, price: 7.5},
                        {value: 4, price: 7.5},
                        {value: 5, price: 7.5},
                        {value: 6, price: 7.5},
                        {value: 8, price: 7.5},
                        {value: 10, price: 7.5},
                        {value: 15, price: 7.5},
                        {value: 20, price: 7.5},
                        {value: 25, price: 7.5},
                        {value: 30, price: 7.5},
                        {value: 40, price: 7.5},
                    ],
                    dimensions: [
                        {value: 0, length: 2500, width: 1250},
                    ],
                    density: 1.2
                },
                {
                    value: 10,
                    text: 'ABS',
                    ths: [
                        {value: 1.5, price: 3.6},
                        {value: 2, price: 3.6},
                        {value: 3, price: 3.6},
                        {value: 4, price: 3.6},
                        {value: 5, price: 3.6},
                        {value: 6, price: 3.6},
                        {value: 8, price: 3.6},
                        {value: 10, price: 3.6},
                        {value: 12, price: 3.6},
                        {value: 15, price: 3.6},
                        {value: 20, price: 3.6},
                        {value: 25, price: 3.6},
                    ],
                    dimensions: [
                        {value: 0, length: 2000, width: 1250},
                    ],
                    density: 1.076
                },
                {
                    value: 11,
                    text: 'Гетинакс',
                    ths: [
                        {value: 0.5, price: 5.5},
                        {value: 1, price: 4.5},
                        {value: 1.5, price: 4.5},
                        {value: 2, price: 3.8},
                        {value: 2.5, price: 3.8},
                        {value: 3, price: 3.3},
                        {value: 4, price: 3.3},
                        {value: 5, price: 3.3},
                        {value: 6, price: 3.3},
                        {value: 8, price: 3.3},
                        {value: 10, price: 3.3},
                        {value: 12, price: 3.3},
                        {value: 15, price: 3.3},
                        {value: 20, price: 3.3},
                        {value: 25, price: 3.3},
                        {value: 30, price: 3.3},
                        {value: 40, price: 3.3},
                        {value: 50, price: 3.3},
                    ],
                    dimensions: [
                        {value: 0, length: 2020, width: 1020},
                    ],
                    density: 1.505
                },
                {
                    value: 12,
                    text: 'Фторопласт 100%',
                    ths: [
                        {value: 0.8, price: 11},
                        {value: 1, price: 11},
                        {value: 1.5, price: 11},
                        {value: 2, price: 11},
                        {value: 3, price: 11},
                        {value: 4, price: 11},
                        {value: 5, price: 11},
                        {value: 6, price: 11},
                        {value: 8, price: 11},
                        {value: 10, price: 11},
                        {value: 12, price: 11},
                        {value: 15, price: 11},
                        {value: 20, price: 11},
                        {value: 25, price: 11},
                        {value: 30, price: 11},
                        {value: 40, price: 11},
                        {value: 50, price: 11},
                    ],
                    dimensions: [
                        {value: 0, length: 1000, width: 1000},
                    ],
                    density: 2.4
                },
            ],

        }
    },
    mounted: function () {
        this.material = 0;
        this.onSetMaterial(0);
    },
    computed: {
        result: function () {
            if (this.widthOptions.length === 0) {
                return 'undefined';
            }
            let thickness = parseFloat(this.thickness);
            let density = parseFloat(this.materialInfo.density);
            let usdCourse = parseFloat(this.usdCourse);
            let width = parseFloat(this.width);
            let listWidth = parseFloat(this.widthOptions[this.listWidth].text);
            let length = parseFloat(this.length);
            let listLength = parseFloat(this.lengthOptions[this.listLength].text);
            let price = parseFloat(this.thicknessOptions.find((option) => option.value === this.thickness).price);

            let res;

            if (width === undefined || width > listWidth || width === 0 ||
                length === undefined || length > listLength || length === 0) {
                return 'undefined';
            }
            if (width >= 0.85 * listWidth) {
                this.formulaText = thickness + " * " + density + " * " + (length + 3) + " * " + listLength + " / " + 1.1 + ' / ' + 1000000
                    + ' * ' + price + " * " + usdCourse + " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (length + 3) * listLength / 1.1 / 1000000  * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13

            } else if (length >= 0.85 * listLength) {
                this.formulaText = thickness + " * " + density + " * " + (width + 3) + " * " + listWidth + " / " + 1.2 + ' / ' + 1000000
                    + ' * ' + price + " * " + usdCourse + " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (width + 3) * listWidth / 1.2 / 1000000  * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13

            } else {
                this.formulaText = thickness + " * " + density + " * " + (width + 3) + " * " + (length + 3) + ' / ' + 1000000
                    + ' * ' + 1.331 + ' * ' + price + " * " + usdCourse +
                    " + (" + Math.ceil(length / 1000) + " + " + Math.ceil(width / 1000) + ') * ' + 13;
                res = thickness * density * (width + 3) * (length + 3) / 1000000 * 1.331 * price * usdCourse
                    + (Math.ceil(length / 1000) + Math.ceil(width / 1000)) * 13
            }
            return res;
        }
    },
}
</script>
