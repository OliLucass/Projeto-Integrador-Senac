    using System;
    using System.Collections.Generic;
    using System.Data;
    using System.Linq;
    using System.Text;
    using System.Threading.Tasks;
    using MySql.Data.MySqlClient;
    using Projeto_integrador;



    namespace Projeto_integrador
    {
        class Jogo
        {
            public string Categoria { get; set; }
            public string Titulo { get; set; }
            public string Desenvolvedora { get; set; }
            public string Distribuidora { get; set; }
            public string Informacoes { get; set; }
            public DateTime DataLancamento { get; set; }
            public string RequisitosSistema { get; set; }
            public decimal Valor { get; set; }
            public int Id { get; set; }
            public string ImagemCapa { get; set; }
            public string ImagemCen1 { get; set; }
            public string ImagemCen2 { get; set; }


            public void Inserir()
            {
                Conexao conexao = new Conexao();
                using (var conn = conexao.GetConnection())
                {
                    string sql = @"INSERT INTO jogos 
                    (id_categoria, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1,Imagens_cen2)
                    VALUES 
                    (@id_categoria, @titulo, @desenvolvedora, @distribuidora, @informacoes, @data_lancamento, @req_sistema, @ImagemCapa, @ImagemCen1, @ImagemCen2)";


                    using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                    {
                        cmd.Parameters.AddWithValue("@id_categoria", Categoria);
                        cmd.Parameters.AddWithValue("@titulo", Titulo);
                        cmd.Parameters.AddWithValue("@desenvolvedora", Desenvolvedora);
                        cmd.Parameters.AddWithValue("@distribuidora", Distribuidora);
                        cmd.Parameters.AddWithValue("@informacoes", Informacoes);
                        cmd.Parameters.AddWithValue("@data_lancamento", DataLancamento);
                        cmd.Parameters.AddWithValue("@req_sistema", RequisitosSistema);
                        cmd.Parameters.AddWithValue("@ImagemCapa", ImagemCapa);
                        cmd.Parameters.AddWithValue("@ImagemCen1", ImagemCen1);
                        cmd.Parameters.AddWithValue("@ImagemCen2", ImagemCen2);

                        conn.Open();
                        cmd.ExecuteNonQuery();
                    }
                }
            }

            public void Atualizar()
            {
                Conexao conexao = new Conexao();
                using (var conn = conexao.GetConnection())
                {
                    string sql = @"UPDATE jogos SET 
                id_categoria = @id_categoria,
                titulo = @titulo,
                desenvolvedora = @desenvolvedora,
                distribuidora = @distribuidora,
                informacoes = @informacoes,
                data_lancamento = @data_lancamento,
                req_sistema = @req_sistema,
                Valor = @Valor,
                Imagens_jogos = @Imagens_jogos,
                Imagens_cen1 = @Imagem_cen1,
                Imagens_cen2 = @Imagem_cen2
                WHERE id_play = @id";

                    using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                    {
                        cmd.Parameters.AddWithValue("@id_categoria", Categoria);
                        cmd.Parameters.AddWithValue("@titulo", Titulo);
                        cmd.Parameters.AddWithValue("@desenvolvedora", Desenvolvedora);
                        cmd.Parameters.AddWithValue("@distribuidora", Distribuidora);
                        cmd.Parameters.AddWithValue("@informacoes", Informacoes);
                        cmd.Parameters.AddWithValue("@data_lancamento", DataLancamento);
                        cmd.Parameters.AddWithValue("@req_sistema", RequisitosSistema);
                        cmd.Parameters.AddWithValue("@Valor", Valor);
                        cmd.Parameters.AddWithValue("@Imagens_jogos", ImagemCapa);
                        cmd.Parameters.AddWithValue("@Imagem_cen1", ImagemCen1);     
                        cmd.Parameters.AddWithValue("@Imagem_cen2", ImagemCen2);     
                        cmd.Parameters.AddWithValue("@id", Id);

                        conn.Open();
                        cmd.ExecuteNonQuery();
                    }
                }
            }



            public void Remover()
            {
                Conexao conexao = new Conexao();
                using (var conn = conexao.GetConnection())
                {
                    string sql = "DELETE FROM jogos WHERE id_play = @id_play";

                    using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                    {
                        cmd.Parameters.AddWithValue("@id_play", Id);
                        conn.Open();
                        cmd.ExecuteNonQuery();
                    }
                }
            }


            public static DataTable ListarTodos()
            {
                Conexao conexao = new Conexao();
                using (var conn = conexao.GetConnection())
                {
                    string sql = "SELECT * FROM jogos ";
                    using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                    {
                        DataTable dt = new DataTable();
                        da.Fill(dt);
                        return dt;
                    }
                }
            }
        }
    }
