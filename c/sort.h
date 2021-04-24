#ifndef INC_SORT
#define INC_SORT
#define NUM 2

int load(char *, int[][NUM]);
void store_file(int file_data_num);
void swap(int arr[][NUM], int i, int j);
void quick_sort(int arr[][NUM], int left, int right);
void add_rank(int score_arr[][NUM], int arr_num);
// int compare_int(const void *x, const void *y);

int rank_value[200][NUM]; //{得点,順位}のセット

#endif