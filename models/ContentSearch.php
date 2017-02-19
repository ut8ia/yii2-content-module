<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ut8ia\contentmodule\models\Content;

/**
 * ContentSearch represents the model behind the search form about `ut8ia\contentmodule\models\Content`.
 */
class ContentSearch extends Content
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'text', 'date','lang_id','rubric_id','section_id','section'], 'safe'],
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
        $query = Content::find()
//            ->orderBy(['id'=>SORT_DESC])
            ->with('section','rubric','author');

        if(isset(Yii::$app->controller->module->sectionId)){
            $params['ContentSearch']['section_id'] = Yii::$app->controller->module->sectionId;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>['attributes'=>['id','publication_date','name']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'lang_id' => $this->lang_id,
            'rubric_id' => $this->rubric_id,
            'section_id' => $this->section_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
