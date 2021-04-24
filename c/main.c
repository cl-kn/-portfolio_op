#include <stdio.h>
#include "sort.h"

int main(void)
{
    int file_data_num;                //ファイル内データ数
    char filename[] = "data.txt"; //読み込むファイル名

    file_data_num = load(filename, rank_value); //ファイル内データ数取得

    quick_sort(rank_value, 0, file_data_num - 1); //データの降順ソート
    add_rank(rank_value, file_data_num);          //ランク付け処理
    store_file(file_data_num);                    //ファイル書き出し

    return 0;
}