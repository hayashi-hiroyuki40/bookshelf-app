<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorUser = User::first();
        if (! $authorUser) {
            return;
        }

        $genreIdsByName = Genre::pluck('id', 'name');

        $books = [
            1 => [
                'title' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'published_date' => '1905-01-01',
                'description' => '猫の視点から明治期の知識人や社会を風刺した代表作です。',
                'genres' => ['小説'],
            ],
            2 => [
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'description' => '人間関係やコミュニケーションの基本原則を解説した古典的名著です。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            3 => [
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'description' => '日々のプログラミングで読みやすく分かりやすいコードを書くための実践書です。',
                'genres' => ['技術書'],
            ],
            4 => [
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'description' => '主体性や目的意識など、人格と成果を高めるための原則をまとめた一冊です。',
                'genres' => ['ビジネス', '自己啓発'],
            ],
            5 => [
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'description' => '気性がまっすぐな主人公が赴任先で奮闘する痛快な長編小説です。',
                'genres' => ['小説'],
            ],
            6 => [
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'description' => '認知革命から現代まで、ホモ・サピエンスの歴史を俯瞰する教養書です。',
                'genres' => ['歴史', '科学'],
            ],
            7 => [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'description' => '保守しやすく健全なソフトウェア設計・実装の考え方を学べる定番書です。',
                'genres' => ['技術書'],
            ],
            8 => [
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'description' => 'アドラー心理学の思想を青年と哲人の対話形式で分かりやすく解き明かします。',
                'genres' => ['自己啓発'],
            ],
            9 => [
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'description' => 'お笑いの世界に生きる芸人たちの葛藤と絆を描いた文学作品です。',
                'genres' => ['小説'],
            ],
            10 => [
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'description' => '思い込みを捨ててデータと事実に基づき世界を捉える思考法を説きます。',
                'genres' => ['ビジネス', '科学'],
            ],
            11 => [
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'description' => '輸送コンテナの普及がグローバル経済や物流をどう変えたかを追う記録です。',
                'genres' => ['ビジネス', '歴史'],
            ],
        ];

        foreach ($books as $number => $bookData) {
            $book = Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                [
                    'user_id' => $authorUser->id,
                    'title' => $bookData['title'],
                    'author' => $bookData['author'],
                    'published_date' => $bookData['published_date'],
                    'description' => $bookData['description'],
                    'image_url' => "https://placehold.co/200x300/e2e8f0/475569?text={$number}",
                ]
            );

            $genreIds = collect($bookData['genres'])
                ->map(fn (string $name) => $genreIdsByName[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            $book->genres()->sync($genreIds);
        }
    }
}
