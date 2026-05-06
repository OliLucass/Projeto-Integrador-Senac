using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Projeto_integrador
{
    public class Buscas
    {

        public bool busca_email(string imail)
        {
            string sql = "SELECT COUNT(*) FROM cadastro WHERE email = @imail";
            Conexao conexao = new Conexao();

            using (var conn = conexao.GetConnection())
            {
                using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@imail", imail);
                    conn.Open();
                    int count = Convert.ToInt32(cmd.ExecuteScalar());
                    return count > 0; // true se já existe
                }
            }
        }

        public bool busca_cpf(string cpff)
        {
            string sql = "SELECT COUNT(*) FROM cadastro WHERE cpf = @cpff";
            Conexao conexao = new Conexao();

            using (var conn = conexao.GetConnection())
            {
                using (MySqlCommand cmd = new MySqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@cpff", cpff);
                    conn.Open();
                    int count = Convert.ToInt32(cmd.ExecuteScalar());
                    return count > 0; // true se já existe
                }
            }
        }

        public DataTable teste()
        {

            string sql = "SELECT * FROM cadastro";
            Conexao conexao = new Conexao();
            using (var conn = conexao.GetConnection())
            {
                using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                {

                    DataTable dt = new DataTable();
                    da.Fill(dt);
                    return dt;
                }
            }
        }

        public DataTable procura_titulo(string nome)
        {
            string nomeBusca = nome;
            string sql = "SELECT id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos WHERE titulo LIKE @nome";
            Conexao conexao = new Conexao();
            using (var conn = conexao.GetConnection())
            {
                using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                {
                    da.SelectCommand.Parameters.AddWithValue("@nome", $"%{nomeBusca}%");
                    DataTable dt = new DataTable();
                    da.Fill(dt);
                    return dt;
                }
            }


        }

        public DataTable procura_desenvolvedora(string nome)
        {
            string nomeBusca = nome;
            string sql = "SELECT id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos WHERE desenvolvedora LIKE @nome";
            Conexao conexao = new Conexao();
            using (var conn = conexao.GetConnection())
            {
                using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                {
                    da.SelectCommand.Parameters.AddWithValue("@nome", $"%{nomeBusca}%");
                    DataTable dt = new DataTable();
                    da.Fill(dt);
                    return dt;
                }
            }
        }

        public DataTable procura_distribuidora(string nome)
        {
            string nomeBusca = nome;
            string sql = "SELECT id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos WHERE distribuidora LIKE @nome";
            Conexao conexao = new Conexao();
            using (var conn = conexao.GetConnection())
            {
                using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                {
                    da.SelectCommand.Parameters.AddWithValue("@nome", $"%{nomeBusca}%");
                    DataTable dt = new DataTable();
                    da.Fill(dt);
                    return dt;
                }
            }
        }

        public DataTable procura_informacoes(string nome)
        {
            string nomeBusca = nome;
            string sql = "SELECT id_play, titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos WHERE titulo LIKE @nome";
            Conexao conexao = new Conexao();
            using (var conn = conexao.GetConnection())
            {
                using (MySqlDataAdapter da = new MySqlDataAdapter(sql, conn))
                {
                    da.SelectCommand.Parameters.AddWithValue("@nome", $"%{nomeBusca}%");
                    DataTable dt = new DataTable();
                    da.Fill(dt);
                    return dt;
                }
            }
        }

        public DataTable jubarte()
        {
            Conexao conexao = new Conexao();
            using (MySqlConnection conn = conexao.GetConnection())
            {
                string query = "SELECT titulo, desenvolvedora, distribuidora, informacoes, data_lancamento, req_sistema, Imagens_jogos, Imagens_cen1, Imagens_cen2 FROM jogos";
                MySqlCommand cmd = new MySqlCommand(query, conn);
                MySqlDataAdapter da = new MySqlDataAdapter(cmd);
                DataTable tabela = new DataTable();
                da.Fill(tabela);
                return tabela;
            }
        }
    }
}
