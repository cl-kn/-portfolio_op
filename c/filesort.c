//***************************************************
/** 演習８
 * ・1行ずつ得点が記入されたテキストファイルを読み込み、降順にソートして順位付けを行い、別のファイルに出力するプログラム
 * ・重複する得点は同じ順位にする
 *
 * 10/22,23 修正版
 * ・配列を rank_value[200][2] に統一
 * ・add_rank()の処理を、sort()内に統一
 *
 * 10/27 改修版
 * ・バブルソートからクイックソートへ変更
 *
 * 11/5 修正
 * ・コメントアウト個所を、 #if 0 ～ #endif へ変更
*/
//***************************************************

#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#define NUM 2

int load(char *, int[][NUM]);
void store_file(int file_data_num);
void swap2(int arr[][NUM], int i, int j);
void quick_sort(int arr[][NUM], int left, int right);
void add_rank(int score_arr[][NUM], int arr_num);
int compare_int(const void *x, const void *y);

int rank_value[200][NUM]; //{得点,順位}のセット

int main(void)
{
    int file_data_num;                //ファイル内データ数
    char filename[] = "testdata.txt"; //読み込むファイル名

    file_data_num = load(filename, rank_value); //ファイル内データ数取得

    quick_sort(rank_value, 0, file_data_num - 1); //データの降順ソート
    add_rank(rank_value, file_data_num);          //ランク付け処理
    store_file(file_data_num);                    //ファイル書き出し

    return 0;
}

// qsort()
int compare_int(const void *x, const void *y)
{
    return *(int *)x - *(int *)y;
}

//ファイル入力関数、読み取ったデータ数を返す
int load(char *filename, int arr[][NUM])
{
    int i, n = 0;
    FILE *fp;

    if ((fp = fopen(filename, "r")) == NULL)
    {
        printf("**** can't open the file ^v^ ****\n");
        exit(1);
    }

    while (!feof(fp) && n < 256)
    {
        fscanf(fp, "%d", &(arr[n][0]));
        n++;
    }

    fclose(fp);

    n = n - 1; //EOF行の除外

    return n;
}

//ファイル出力関数
void store_file(int file_data_num)
{
    FILE *fp;
    int i;
    char store_filename[64];

    printf("Enter a output file name >> ");
    scanf("%s", store_filename);

    fp = fopen(store_filename, "w");

    fprintf(fp, "score,rank\n");
    for (i = 0; i < file_data_num; i++)
    {
        fprintf(fp, "%d,%d\n", rank_value[i][0], rank_value[i][1]);
    }

    fclose(fp);
}

//値の入れ替え関数
void swap2(int arr[][NUM], int i, int j)
{
    int temp = arr[i][0];
    arr[i][0] = arr[j][0];
    arr[j][0] = temp;
}

//値の順位付け関数（重複得点は同順位）
void add_rank(int score_arr[][NUM], int arr_num)
{
    int i, j;
    int rank = 0;  //順位用変数
    int value = 0; //得点の同値比較用変数

    score_arr[0][1] = rank;

    for (i = 0; i < arr_num; i++)
    {
        if (score_arr[i][0] != value)
        {
            rank++;
        }
        score_arr[i][1] = rank;
        value = score_arr[i][0];
    }
}

//クイックソート（降順）
#if 1
void quick_sort(int arr[][NUM], int left, int right)
{
    int i, j;
    int pivot;
    int rank = 0;
    int value = 0;

    arr[0][1] = rank;

    i = left;  //ソートする配列の最小要素の添字
    j = right; //ソートする配列の最大要素の添字

    pivot = arr[(left + right) / 2][0]; //基準値を配列の中央付近にとる

    while (1)
    {
        while (arr[i][0] > pivot)
        {        //pivotより大きい値が
            i++; //出るまでiを増加させる
        }

        while (pivot > arr[j][0])
        {        //pivotより大きい値が
            j--; //出るまでｊを減少
        }
        if (i >= j)
        {
            break;
        }
        swap2(arr, i, j);

        //次のデータ
        i++;
        j--;
    }

    //基準値の左に 2 以上要素があれば、左の配列をクイックソート
    if (left < i - 1)
    {
        quick_sort(arr, left, i - 1);
    }
    //基準値の右に 2 以上要素があれば、右の配列をクイックソート
    if (j + 1 < right)
    {
        quick_sort(arr, j + 1, right);
    }
}
#endif

//降順ソート & ランク付け関数
#if 0
void sort_rank(int array[][NUM], int arr_num)
{
    int i, j;
    int tmp = 0;
    int rank = 0;
    int value = 0;

    array[0][1] = rank;

    for (i = 0; i < arr_num; i++)
    {
        for (j = i + 1; j < arr_num; j++)
        {
            if (array[i][0] < array[j][0])
            {
                swap(&array[i][0], &array[j][0]);
            }
        }
        if (array[i][0] != value)
        {
            rank++;
        }
        array[i][1] = rank;
        value = array[i][0];
    }
}
#endif

//値の入れ替え関数
#if 0
void swap(int *x, int *y)
{
    int tmp = *x;
    *x = *y;
    *y = tmp;
}
#endif

//ファイル中身表示用関数
#if 0
void print_file(char *file_name)
{
    FILE *fp;

    if ((fp = fopen(file_name, "r")) == NULL)
    {
        printf("**** can't open the file ^v^ ****\n");
        exit(1);
    }
}
#endif

//配列コピー関数：memcpy()不使用
#if 0
void copy_arr(int *score_arr, int arr_num)
{
    int i, j;
    for (i = 0; i < arr_num; i++)
    {
        rank_value[i][0] = score_arr[i];
    }
}
#endif

//値の順位付け関数（重複得点は同順位）
#if 0
void add_rank(int score_arr[][NUM], int arr_num)
{
    int i, j;
    int rank = 0;  //順位用変数
    int value = 0; //得点の同値比較用変数

    // copy_arr(score_arr, arr_num);

    score_arr[0][1] = rank;

    for (i = 0; i < arr_num; i++)
    {
        if (score_arr[i][0] != value)
        {
            rank++;
        }
        score_arr[i][1] = rank;
        value = score_arr[i][0];
    }
}
#endif

/**
 * 順位付けメモ
 * 100,1
 * 99,2
 * 98,3
 * 98,3
 * 97,5
 * ↑のように、得点に対して順位をつける
 * 重複点数は同位とする
 *
 * 「方法１」？
 * ・順位を表示する配列を用意
 * ・配列内に得点を格納し、添え字を順位として利用
 *
 * rank[200] = {0,100,99,98,97,・・・} ※先頭に0を付与し、1位から表示
 *
 * 「方法２」？
 * ・2次元配列を用意し、降順ソート後の配列をコピーする
 * ・{得点,順位}のセット
 *
 * {{100,1},{99,2},{98,3},{98,3},{97,5}・・・}
*/