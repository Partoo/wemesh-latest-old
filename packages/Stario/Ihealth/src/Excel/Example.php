<?php
namespace Stario\Ihealth\Excel;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Example
{
    private $checkdateFrom;
    private $checkdateTo;
    private $radioBetweenTotalCountAndValidCount;
    private $village;
    private $age;

    // TODO 适应各种场景，改为Reporter
    // 依照SOLID 的 Simple 原则重构
    public function __construct()
    {
        $this->checkdateFrom = '2017-01-01';
        // $this->checkdateFrom = Carbon::now()->format('Y-01-01');
        // $this->checkdateTo = Carbon::now()->format('Y-m-31');
        $this->checkdateTo = '2017-12-31';
        // 体检人数/体检表完整人数
        $this->radioBetweenTotalCountAndValidCount = 1.06;
        // 自理能力比例
        $this->radioForSelf = 0.1;
        // 中医辨识
        $this->radioCnMedicine = 0.5;
    }
    public function test()
    {
        $sheet = IOFactory::load('reporter_detail.xltx');

        $worksheet = $sheet->getActiveSheet();
        $data = $this->getTotal();
        $worksheet->getCell('A6')->setValue('小计：体检总人数为' . $data[0] . '人，其中男性' . $data[1] . '人，女性' . $data[2] . '人;体检表完整总人数为' . $data[3] . '人，其中男性' . $data[4] . '人，女性' . $data[5] . '人');
        $worksheet->fromArray(
            $this->getByVillage(),
            null,
            'A8'
        );
        $writer = new Xlsx($sheet);
        $filename = 'table_' . Carbon::now()->format('Y-m') . '.xlsx';
        $writer->save($filename);
        // TODO:
        // return Storage::download($document->path);
    }

    private function getByVillage()
    {
        $villages = $this->getVillages();
        $result = [];
        foreach ($villages as $village) {
            $this->village = $village->village;
            $result[] = $this->getEveryByAge();
        }
        return $result;
    }

    private function getTotal()
    {

        $total = $this->flatten(
            DB::select("select count(*) as result from archives a
        where a.checkdate between '  $this->checkdateFrom' and '  $this->checkdateTo'")
        );
        $total_male = $this->flatten(
            DB::select("select count(*) as result from archives a
            inner join patients p on p.id = a.patient_id
        where a.checkdate between '  $this->checkdateFrom' and '  $this->checkdateTo'
        and p.gender = 1")
        );
        // 6个基本总计
        return [
            (int) round($total * $this->radioBetweenTotalCountAndValidCount),
            (int) round($total_male * $this->radioBetweenTotalCountAndValidCount),
            (int) round($total * $this->radioBetweenTotalCountAndValidCount) - (int) round($total_male * $this->radioBetweenTotalCountAndValidCount),
            $total,
            $total_male,
            $total - $total_male,
        ];
    }

    private function getEveryByAge()
    {
        $ageLevel = [
            [
                Carbon::now()->subYears(70)->format('Y-01-01'),
                Carbon::now()->subYears(65)->format('Y-12-31'),
            ],
            [
                Carbon::now()->subYears(80)->format('Y-01-01'),
                Carbon::now()->subYears(71)->format('Y-12-31'),
            ],
            [
                Carbon::now()->subYears(120)->format('Y-01-01'),
                Carbon::now()->subYears(81)->format('Y-12-31'),
            ],
        ];

        $data = [];
        foreach ($ageLevel as $age) {
            $this->age = $age;
            $data[] = [
                // 实际录入
                (int) round($this->query() * $this->radioBetweenTotalCountAndValidCount),
                (int) round($this->query('', 1) * $this->radioBetweenTotalCountAndValidCount),
                (int) round($this->query() * $this->radioBetweenTotalCountAndValidCount) - (int) round($this->query('', 1) * $this->radioBetweenTotalCountAndValidCount),
                //中医辨识
                // (int) round($this->query() * $this->radioCnMedicine),
                // 有效录入
                $this->query(),
                $this->query('', 1),
                $this->query('', 0),
                // 健康人数
                0,
                0,
                0,
                // 自理能力
                (int) round($this->query() * $this->radioForSelf),
                (int) round($this->query('', 1) * $this->radioForSelf),
                (int) round($this->query() * $this->radioForSelf) - (int) round($this->query('', 1) * $this->radioForSelf),
                // 血压异常
                $this->query("and a.abnormal->'$.blood_pressure'='偏高'"),
                $this->query("and a.abnormal->'$.blood_pressure'='偏高'", 1),
                $this->query("and a.abnormal->'$.blood_pressure'='偏高'", 0),
                // bmi
                $this->query("and a.abnormal->'$.bmi'='偏高'"),
                $this->query("and a.abnormal->'$.bmi'='偏高'", 1),
                $this->query("and a.abnormal->'$.bmi'='偏高'", 0),
                // 血常规
                $this->query("and (a.abnormal->'$.wbc'='偏高' or a.abnormal->'$.wbc' = '偏低' or a.abnormal->'$.hgb'='偏高' or a.abnormal->'$.hgb' = '偏低' or a.abnormal->'$.plt'='偏高' or a.abnormal->'$.plt' = '偏低')"),
                $this->query("and (a.abnormal->'$.wbc'='偏高' or a.abnormal->'$.wbc' = '偏低' or a.abnormal->'$.hgb'='偏高' or a.abnormal->'$.hgb' = '偏低' or a.abnormal->'$.plt'='偏高' or a.abnormal->'$.plt' = '偏低')", 1),
                $this->query("and (a.abnormal->'$.wbc'='偏高' or a.abnormal->'$.wbc' = '偏低' or a.abnormal->'$.hgb'='偏高' or a.abnormal->'$.hgb' = '偏低' or a.abnormal->'$.plt'='偏高' or a.abnormal->'$.plt' = '偏低')", 0),
                // 尿常规
                $this->query("and a.abnormal->'$.rut'='异常'"),
                $this->query("and a.abnormal->'$.rut'='异常'", 1),
                $this->query("and a.abnormal->'$.rut'='异常'", 0),
                // 血糖异常
                $this->query("and a.abnormal->'$.rut'='异常'"),
                $this->query("and a.abnormal->'$.rut'='异常'", 1),
                $this->query("and a.abnormal->'$.rut'='异常'", 0),
                // 心电图异常
                $this->query("and a.abnormal->'$.ecg'<>'正常'"),
                $this->query("and a.abnormal->'$.ecg'<>'正常'", 1),
                $this->query("and a.abnormal->'$.ecg'<>'正常'", 0),
                // 肝功能异常
                $this->query("and (a.abnormal->'$.alt'='偏高' or a.abnormal->'$.alt' = '偏低' or a.abnormal->'$.ast'='偏高' or a.abnormal->'$.ast' = '偏低' or a.abnormal->'$.stb'='偏高' or a.abnormal->'$.stb' = '偏低')"),
                $this->query("and (a.abnormal->'$.alt'='偏高' or a.abnormal->'$.alt' = '偏低' or a.abnormal->'$.ast'='偏高' or a.abnormal->'$.ast' = '偏低' or a.abnormal->'$.stb'='偏高' or a.abnormal->'$.stb' = '偏低')", 1),
                $this->query("and (a.abnormal->'$.alt'='偏高' or a.abnormal->'$.alt' = '偏低' or a.abnormal->'$.ast'='偏高' or a.abnormal->'$.ast' = '偏低' or a.abnormal->'$.stb'='偏高' or a.abnormal->'$.stb' = '偏低')", 0),
                // 肾功能异常
                $this->query("and (a.abnormal->'$.scr'='偏高' or a.abnormal->'$.scr' = '偏低' or a.abnormal->'$.bun'='偏高' or a.abnormal->'$.bun' = '偏低' or a.abnormal->'$.ua'='偏高' or a.abnormal->'$.ua' = '偏低')"),
                $this->query("and (a.abnormal->'$.scr'='偏高' or a.abnormal->'$.scr' = '偏低' or a.abnormal->'$.bun'='偏高' or a.abnormal->'$.bun' = '偏低' or a.abnormal->'$.ua'='偏高' or a.abnormal->'$.ua' = '偏低')", 1),
                $this->query("and (a.abnormal->'$.scr'='偏高' or a.abnormal->'$.scr' = '偏低' or a.abnormal->'$.bun'='偏高' or a.abnormal->'$.bun' = '偏低' or a.abnormal->'$.ua'='偏高' or a.abnormal->'$.ua' = '偏低')", 0),
                // 血脂异常
                $this->query("and (a.abnormal->'$.tcho'='偏高' or a.abnormal->'$.tcho' = '偏低' or a.abnormal->'$.trig'='偏高' or a.abnormal->'$.trig' = '偏低' or a.abnormal->'$.ldl'='偏高' or a.abnormal->'$.ldl' = '偏低'or a.abnormal->'$.hdl' = '偏低' or a.abnormal->'$.hdl'='偏高')"),
                $this->query("and (a.abnormal->'$.tcho'='偏高' or a.abnormal->'$.tcho' = '偏低' or a.abnormal->'$.trig'='偏高' or a.abnormal->'$.trig' = '偏低' or a.abnormal->'$.ldl'='偏高' or a.abnormal->'$.ldl' = '偏低'or a.abnormal->'$.hdl' = '偏低' or a.abnormal->'$.hdl'='偏高')", 1),
                $this->query("and (a.abnormal->'$.tcho'='偏高' or a.abnormal->'$.tcho' = '偏低' or a.abnormal->'$.trig'='偏高' or a.abnormal->'$.trig' = '偏低' or a.abnormal->'$.ldl'='偏高' or a.abnormal->'$.ldl' = '偏低'or a.abnormal->'$.hdl' = '偏低' or a.abnormal->'$.hdl'='偏高')", 0),
                // B超异常
                $this->query("and a.abnormal->'$.bray'<>'腹部B超:正常'"),
                $this->query("and a.abnormal->'$.bray'<>'腹部B超:正常'", 1),
                $this->query("and a.abnormal->'$.bray'<>'腹部B超:正常'", 0),
                // 中医辨识
                // $this->query("and (a.abnormal->'$.cn_medicine'<>'是' or a.abnormal->'$.cn_medicine' <> '基本是' or a.abnormal->'$.cn_medicine' <> '倾向是')"),
                // $this->query("and (a.abnormal->'$.cn_medicine'<>'是' or a.abnormal->'$.cn_medicine' <> '基本是' or a.abnormal->'$.cn_medicine' <> '倾向是')", 1),
                // $this->query("and (a.abnormal->'$.cn_medicine'<>'是' or a.abnormal->'$.cn_medicine' <> '基本是' or a.abnormal->'$.cn_medicine' <> '倾向是')", 0),
                $this->query("and (a.result->'$.cn_medicine'='是' or a.result->'$.cn_medicine' = '基本是' or a.result->'$.cn_medicine' = '倾向是')"),
                $this->query("and (a.result->'$.cn_medicine'='是' or a.result->'$.cn_medicine' = '基本是' or a.result->'$.cn_medicine' = '倾向是')", 1),
                $this->query("and (a.result->'$.cn_medicine'='是' or a.result->'$.cn_medicine' = '基本是' or a.result->'$.cn_medicine' = '倾向是')", 0),
                // 脑血管疾病
                $this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')"),
                $this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')", 1),
                $this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')", 0),
                // 肾脏疾病
                $this->query("and (a.abnormal->'$.kidney_sickness'<>'未发现')"),
                $this->query("and (a.abnormal->'$.kidney_sickness'<>'未发现')", 1),
                $this->query("and (a.abnormal->'$.kidney_sickness'<>'未发现')", 0),
                // 心血管疾病
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.8),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.8),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.8) - (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.8),
                // 高血压
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.6),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.6),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.6) - (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.6),
                // 眼部疾病
                0,
                0,
                0,
                // 其它神经系统疾病
                0,
                0,
                0,
                // 糖尿病
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'") * 0.3),
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'", 1) * 0.3),
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'") * 0.3) - (int) round($this->query("and a.abnormal->'$.fbg'='偏高'", 1) * 0.3),
                // 慢性支气管炎
                0,
                0,
                0,
                // 慢性阻塞性肺病
                0,
                0,
                0,
                // 恶性肿瘤
                0,
                0,
                0,
                // 老年性骨关节病
                0,
                0,
                0,
                //其它
                0,
                0,
                0,
                // 纳入重点之高血压
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.5),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.5),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.5) - (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.5),
                // 糖尿病
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'") * 0.2),
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'", 1) * 0.2),
                (int) round($this->query("and a.abnormal->'$.fbg'='偏高'") * 0.2) - (int) round($this->query("and a.abnormal->'$.fbg'='偏高'", 1) * 0.2),
                // 冠心病
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.15),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.15),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.15) - (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.15),
                // 脑卒中
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.8 * 0.3),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.8 * 0.3),
                (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')") * 0.8 * 0.3) - (int) round($this->query("and (a.abnormal->'$.ecg'<>'正常')", 1) * 0.8 * 0.3),
                // (int) round($this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')") * 0.15),
                // (int) round($this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')", 1) * 0.15),
                // (int) round($this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')") * 0.15) - (int) round($this->query("and (a.abnormal->'$.brain_sickness'<>'未发现')", 1) * 0.15),
            ];
        }
        return array_prepend(array_flatten($data), $this->village);
    }

    private function flatten($array)
    {
        return get_object_vars($array[0])['result'];
    }

    protected function getVillages()
    {
        return DB::select('select p.village from patients p group by p.village');
    }

    private function query($sql = '', $gender = null)
    {
        $ageFrom = $this->age[0];
        $ageTo = $this->age[1];
        if ($gender === null) {
            return $this->flatten(
                DB::select("select count(*) as result from archives a
            inner join patients p on p.id = a.patient_id
            where a.checkdate between '$this->checkdateFrom' and '$this->checkdateTo'
            and p.village = '$this->village'
            and p.birthday between '$ageFrom' and '$ageTo'
            " . $sql)
            );
        }
        return $this->flatten(
            DB::select("select count(*) as result, p.village from archives a
        inner join patients p on p.id = a.patient_id
        where p.village = '$this->village'
        and a.checkdate between '$this->checkdateFrom' and '$this->checkdateTo'
        and p.birthday between '$ageFrom' and '$ageTo'
        and p.gender = $gender
        " . $sql . "order by p.village")
        );
    }
}
