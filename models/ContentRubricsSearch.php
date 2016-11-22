<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ut8ia\contentmodule\models\ContentRubrics;

/**
 * ContentSearch represents the model behind the search form about `ContentRubrics`.
 */
class ContentRubricsSearch extends ContentRubrics
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','section_id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ContentRubrics::find()->with('section');

        if(isset(Yii::$app->controller->module->sectionId)){
            $params['ContentRubricsSearch']['section_id'] = Yii::$app->controller->module->sectionId;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'section_id' => $this->section_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
