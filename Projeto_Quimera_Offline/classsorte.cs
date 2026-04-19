using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySql.Data.MySqlClient;
using static Projeto_integrador.RepositorioJogos;

namespace Projeto_integrador
{
    public class RepositorioJogos
    {
        private string _connectionString = "server=10.37.44.72;user id=root;password=root;database=projeto_quimera";

        public class Jogo
        {
            public string Titulo { get; set; }
            public string Imagem { get; set; }
            public string Trailer { get; set; }
        }

        public class Categoria
        {
            public int Id { get; set; }
            public string Nome { get; set; }
        }

        public List<Categoria> ObterCategorias()
        {
            var categorias = new List<Categoria>();

            using (var conexao = new MySqlConnection(_connectionString))
            {
                conexao.Open();
                string sql = "SELECT id_categoria, tipo_categoria FROM categorias";

                using (var cmd = new MySqlCommand(sql, conexao))
                using (var reader = cmd.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        categorias.Add(new Categoria
                        {
                            Id = Convert.ToInt32(reader["id_categoria"]),
                            Nome = reader["tipo_categoria"].ToString()
                        });
                    }
                }
            }

            return categorias;
        }

        public bool UsuarioExiste(string username)
        {
            using (var conn = new MySqlConnection(_connectionString))
            {
                conn.Open();
                string sql = "SELECT COUNT(*) FROM cadastro WHERE nome_user = @nome";
                using (var cmd = new MySqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@nome", username);
                    long count = Convert.ToInt64(cmd.ExecuteScalar());
                    return count > 0;
                }
            }
        }

        public bool UsuarioPossuiJogos(string username)
        {
            using (var conn = new MySqlConnection(_connectionString))
            {
                conn.Open();
                string sql = @"SELECT COUNT(*) 
                               FROM minha_biblioteca b
                               INNER JOIN cadastro c ON b.id_user = c.id_user
                               WHERE c.nome_user = @nome";
                using (var cmd = new MySqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@nome", username);
                    long count = Convert.ToInt64(cmd.ExecuteScalar());
                    return count > 0;
                }
            }
        }

        public Jogo SortearJogo(string modo = "loja", string usuario = "", int idCategoria = 0)
        {
            var lista = new List<Jogo>();

            using (var conexao = new MySqlConnection(_connectionString))
            {
                conexao.Open();

                string sql;
                if (modo == "loja")
                {
                    sql = (idCategoria > 0)
                        ? "SELECT Titulo, Imagens_jogos, Trailers FROM jogos WHERE id_categoria = @idCategoria"
                        : "SELECT Titulo, Imagens_jogos, Trailers FROM jogos";
                }
                else
                {
                    sql = (idCategoria > 0)
                        ? @"SELECT j.Titulo, j.Imagens_jogos, j.Trailers
                            FROM jogos j
                            INNER JOIN minha_biblioteca b ON b.id_play = j.id_play
                            INNER JOIN cadastro c ON c.id_user = b.id_user
                            WHERE c.nome_user = @usuario AND j.id_categoria = @idCategoria"
                        : @"SELECT j.Titulo, j.Imagens_jogos, j.Trailers
                            FROM jogos j
                            INNER JOIN minha_biblioteca b ON b.id_play = j.id_play
                            INNER JOIN cadastro c ON c.id_user = b.id_user
                            WHERE c.nome_user = @usuario";
                }

                using (var cmd = new MySqlCommand(sql, conexao))
                {
                    cmd.Parameters.AddWithValue("@idCategoria", idCategoria);
                    if (modo != "loja") cmd.Parameters.AddWithValue("@usuario", usuario);

                    using (var reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            lista.Add(new Jogo
                            {
                                Titulo = reader["Titulo"]?.ToString() ?? string.Empty,
                                Imagem = reader["Imagens_jogos"]?.ToString() ?? string.Empty,
                                Trailer = reader["Trailers"]?.ToString() ?? string.Empty
                            });
                        }
                    }
                }
            }

            if (lista.Count == 0) return null;

            var rnd = new Random();
            return lista[rnd.Next(lista.Count)];
        }
    }
}

