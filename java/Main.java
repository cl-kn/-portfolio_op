import java.util.*;
import javax.swing.*;
import java.awt.event.*;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.font.*;

public class Main extends JFrame {
    static Main frame;// フレーム作成
    static JButton button;
    static JTextField mytextfield = new JTextField(); // テキスト入力フィールド
    static JButton mybutton = new JButton("実行"); // 実行ボタン
    static JButton mybutton2 = new JButton("Elizaクリア"); // Elizaクリアボタン
    static JTextArea mytextarea = new JTextArea(); // 結果画面フィールド
    static Eliza myEliza = new Eliza();

    public static void main(String[] args) {
        frame = new Main("おはなしEliza");
        frame.setLocationRelativeTo(null); // 画面を中央に表示
        frame.setVisible(true);
    }

    // コンストラクタ
    Main(String title) {
        setTitle(title);
        setSize(640, 640); // 全体フレームサイズ
        setLayout(null);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE); // 閉じるボタンでプログラム終了
        add(mytextfield);
        mytextfield.setBounds(10, 10, 400, 50); // テキスト入力フィールド
        add(mytextarea);
        mytextarea.setBounds(10, 70, 600, 520);// 結果画面フィールド
        add(mybutton);
        mybutton.setBounds(420, 10, 100, 50); // 実行ボタン設置
        add(mybutton2);
        mybutton2.setBounds(520, 10, 100, 50); // Elizaクリアボタン設置

        // 無名クラス
        // ボタンクリックで実行
        mybutton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                mytextarea.setText(mytextarea.getText() + "\n" + mytextfield.getText() + "\n" + "Eliza: 「"
                        + myEliza.speak(mytextfield.getText()) + "」");
                mytextfield.setText("");
            }

        });

        // ボタンクリックでElizaの発言をクリア
        mybutton2.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                mytextarea.setText("");
            }
        });

        // 入力キーに応じて処理
        mytextfield.addKeyListener(new KeyAdapter() {
            public void keyPressed(KeyEvent e) {
                // ESCキー：入力した文字を消去
                if (e.getKeyCode() == KeyEvent.VK_ESCAPE) {
                    mytextfield.setText("");
                }
                // ENTERキー：入力を実行
                if (e.getKeyCode() == KeyEvent.VK_ENTER) {
                    mytextarea.setText(mytextarea.getText() + "\n" + mytextfield.getText() + "\n" + "Eliza: 「"
                            + myEliza.speak(mytextfield.getText()) + "」");
                    mytextfield.setText("");
                }
            }
        });
    }
}

// Elizaクラス
class Eliza {
    public String speak(String str1) {
        int k1 = 0;
        int k2 = 0;

        // equalsメソッドで、引数str1を比較
        // あいさつを返す
        if (str1.contains("こんにちは")) {
            return ("こんにちは、私はElizaです。");
        } else if (str1.contains("おはよう")) {
            return ("おはようございます、私はElizaです。");
        } else if (str1.contains("こんばんは")) {
            return ("こんばんは、私はElizaです。");
        }

        // 時間、時刻に反応
        GregorianCalendar gcalendar = new GregorianCalendar();

        if (str1.contains("時間") || str1.contains("時刻") || str1.contains("何時")) {
            return ("現在時刻は、 AM " + gcalendar.get(Calendar.HOUR) + "時" + gcalendar.get(Calendar.MINUTE) + "分 です。");
        } else if (str1.contains("日付") || str1.contains("曜日") || str1.contains("日にち")) {
            return ("今日の日付は、" + gcalendar.get(Calendar.YEAR) + "年" + (gcalendar.get(Calendar.MONTH) + 1) + "月"
                    + gcalendar.get(Calendar.DATE) + "日 です。");
        }

        // 私は〇〇が好き、に反応
        k1 = str1.indexOf("私は");
        k2 = str1.indexOf("が好き");
        if (k1 >= 0 && k2 >= 0) {
            String str2 = str1.substring(k1 + 2, k2);
            return ("あなたが好きなものは「" + str2 + "」ですね。");
        }

        // 食べ物、好き、に反応
        k1 = str1.indexOf("食べ物"); // 先頭キー
        k2 = str1.indexOf("好き"); // 終端キー
        if (k1 >= 0 && k2 >= 0) {
            java.util.Date d = new java.util.Date();
            long t = d.getTime();
            // 現在時刻を3で割った余りで判別
            if ((t % 3) == 0) {
                return "Elizaの好きな食べ物はありません。";
            } else if ((t % 3) == 1) {
                return "あなたは何の食べ物が好きなのですか？ 「私は〇〇が好き」とお答えください。";
            } else {
                return "今は空腹ではありません。";
            }
        }

        // 未定義の単語に返す返答
        return "すみません、わからない言葉です。";
    }
}