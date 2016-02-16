-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2016 at 10:52 AM
-- Server version: 5.5.45-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `coaching`
--

-- --------------------------------------------------------

--
-- Table structure for table `0user`
--

CREATE TABLE IF NOT EXISTS `0user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `primeiroNome` varchar(50) NOT NULL,
  `trazidoPorId` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `cover` text NOT NULL,
  `dataPagamento` datetime NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `contactoTelefone` int(11) NOT NULL,
  `tipoContrato` int(11) NOT NULL,
  `duracaoContrato` int(11) NOT NULL,
  `sexo` varchar(2) NOT NULL,
  `coach` int(11) NOT NULL,
  `promocao` int(11) NOT NULL,
  `quantidadePromocao` int(11) NOT NULL,
  `activado` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `NIB` bigint(20) NOT NULL,
  `actividade` text NOT NULL,
  `adminId` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `activation_time` datetime NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userId` (`userId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=306 ;

-- --------------------------------------------------------

--
-- Table structure for table `0useralerts`
--

CREATE TABLE IF NOT EXISTS `0useralerts` (
  `userId` int(11) NOT NULL,
  `content` text NOT NULL,
  `visto` int(11) NOT NULL,
  `ref` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `0userstats`
--

CREATE TABLE IF NOT EXISTS `0userstats` (
  `userId` int(11) NOT NULL,
  `input` text NOT NULL,
  `value` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1047 ;

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `achievement` text NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1998 ;

-- --------------------------------------------------------

--
-- Table structure for table `alimentos`
--

CREATE TABLE IF NOT EXISTS `alimentos` (
  `alimentoId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `kcals100g` double NOT NULL,
  `proteina100g` double NOT NULL,
  `carbs100g` double NOT NULL,
  `fat100g` double NOT NULL,
  `fibra100g` double NOT NULL,
  `broLevel` int(11) NOT NULL,
  `valor_emocional` double(3,1) NOT NULL,
  `valor_saúde` double(3,1) NOT NULL,
  `product_group` text NOT NULL,
  `unit` text NOT NULL,
  PRIMARY KEY (`alimentoId`),
  UNIQUE KEY `alimentoId` (`alimentoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Table structure for table `alimentos_excluidos`
--

CREATE TABLE IF NOT EXISTS `alimentos_excluidos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `alimentoId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member` int(11) NOT NULL,
  `nome` text NOT NULL,
  `imagem` text NOT NULL,
  `categoria` text NOT NULL,
  `background` text NOT NULL,
  `adminId` int(11) NOT NULL,
  `video` text NOT NULL,
  `data` datetime NOT NULL,
  `nutrition_facts` text NOT NULL,
  `content` text NOT NULL,
  `recipeType` text NOT NULL COMMENT 'C - Comlpeta; P- Fonte de Proteína ; HC - Fonte de HC , G - Fonte de G; ',
  `aproved` int(11) NOT NULL,
  `subcategoria` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categorias`
--

CREATE TABLE IF NOT EXISTS `blog_categorias` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  PRIMARY KEY (`categoryId`),
  UNIQUE KEY `categoryId` (`categoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `comment` text NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_rating`
--

CREATE TABLE IF NOT EXISTS `blog_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `categoria` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_saved`
--

CREATE TABLE IF NOT EXISTS `blog_saved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `cat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_subcategorias`
--

CREATE TABLE IF NOT EXISTS `blog_subcategorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoriaId` int(11) NOT NULL,
  `subCategoriaId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `nome` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=208 ;

-- --------------------------------------------------------

--
-- Table structure for table `campanhas`
--

CREATE TABLE IF NOT EXISTS `campanhas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inicio` date DEFAULT NULL,
  `fim` date DEFAULT NULL,
  `investimento` int(11) DEFAULT NULL,
  `retorno_monetario` int(11) NOT NULL,
  `retorno_nao_monetario` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `alvos` text COLLATE utf8_unicode_ci NOT NULL,
  `zonas` text COLLATE utf8_unicode_ci,
  `servico` text COLLATE utf8_unicode_ci NOT NULL,
  `comecada_por` text COLLATE utf8_unicode_ci NOT NULL,
  `conclusoes` text COLLATE utf8_unicode_ci,
  `estado` int(2) NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL,
  `started` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cId` (`user_1`,`user_2`),
  KEY `id` (`id`),
  KEY `chat_ibfk_2` (`user_2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_reply`
--

CREATE TABLE IF NOT EXISTS `chat_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_user` int(11) NOT NULL,
  `seen` int(11) NOT NULL,
  `reply` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE IF NOT EXISTS `coach` (
  `coachId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `email` text CHARACTER SET latin1 NOT NULL,
  `telefone` int(11) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `avatar` text CHARACTER SET latin1 NOT NULL,
  `link` text CHARACTER SET latin1 NOT NULL,
  `NIB` text NOT NULL,
  `userId` int(11) NOT NULL,
  UNIQUE KEY `coachId` (`coachId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=176 ;

-- --------------------------------------------------------

--
-- Table structure for table `coachNotes`
--

CREATE TABLE IF NOT EXISTS `coachNotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `nota` text NOT NULL,
  `fase` text NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

-- --------------------------------------------------------

--
-- Table structure for table `coach_last`
--

CREATE TABLE IF NOT EXISTS `coach_last` (
  `coachId` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `coach_notifications`
--

CREATE TABLE IF NOT EXISTS `coach_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coachId` int(11) NOT NULL,
  `notification` text NOT NULL,
  `data` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `coach_says`
--

CREATE TABLE IF NOT EXISTS `coach_says` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `coachId` int(11) NOT NULL,
  `coachSays` text NOT NULL,
  `data` datetime NOT NULL,
  `tipo` text NOT NULL COMMENT 'acompanhamento ou chat',
  `visto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=563 ;

-- --------------------------------------------------------

--
-- Table structure for table `contactos`
--

CREATE TABLE IF NOT EXISTS `contactos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `activo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `contrato`
--

CREATE TABLE IF NOT EXISTS `contrato` (
  `contratoId` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  PRIMARY KEY (`contratoId`),
  UNIQUE KEY `contratoId` (`contratoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idJunior` int(11) NOT NULL,
  `idCoach` int(11) NOT NULL,
  `mJunior` int(11) NOT NULL COMMENT 'msgs por ler',
  `mCoach` int(11) NOT NULL COMMENT 'msgs por ler',
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=128 ;

-- --------------------------------------------------------

--
-- Table structure for table `crm`
--

CREATE TABLE IF NOT EXISTS `crm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `ouviuFalar` text NOT NULL,
  `sugestao` text NOT NULL,
  `conteudo` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `diario`
--

CREATE TABLE IF NOT EXISTS `diario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `imagem` text NOT NULL,
  `texto` text NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145 ;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `emailId` int(11) NOT NULL AUTO_INCREMENT,
  `emailContent` text NOT NULL,
  `emailType` text NOT NULL,
  `emailTitle` text NOT NULL,
  PRIMARY KEY (`emailId`),
  UNIQUE KEY `emailId` (`emailId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_results`
--

CREATE TABLE IF NOT EXISTS `email_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `Lng` double(10,6) NOT NULL,
  `Lat` double(10,6) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `City` varchar(110) NOT NULL,
  `Region` varchar(110) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Table structure for table `exercicios`
--

CREATE TABLE IF NOT EXISTS `exercicios` (
  `exercicioId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `musculoId` int(11) NOT NULL,
  `video` text NOT NULL,
  UNIQUE KEY `exercicioId` (`exercicioId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Table structure for table `ficheiros`
--

CREATE TABLE IF NOT EXISTS `ficheiros` (
  `ficheiroId` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` text NOT NULL,
  `nome` text NOT NULL,
  `pasta` text NOT NULL,
  `location` text NOT NULL,
  `autorAvatar` text NOT NULL,
  PRIMARY KEY (`ficheiroId`),
  UNIQUE KEY `id` (`ficheiroId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `ficheiros_enviados`
--

CREATE TABLE IF NOT EXISTS `ficheiros_enviados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ficheiroId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=210 ;

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE IF NOT EXISTS `follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `editorId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `frases`
--

CREATE TABLE IF NOT EXISTS `frases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text NOT NULL,
  `desc` text NOT NULL,
  `nome` text NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL,
  `nome` text NOT NULL,
  `imagem` text NOT NULL,
  `adminId` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `invite_results`
--

CREATE TABLE IF NOT EXISTS `invite_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inviteId` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1005 ;

-- --------------------------------------------------------

--
-- Table structure for table `manager_actions`
--

CREATE TABLE IF NOT EXISTS `manager_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data-inicio` datetime NOT NULL,
  `data-fim` datetime NOT NULL,
  `teste` text NOT NULL,
  `resultados` text NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `manager_tasks`
--

CREATE TABLE IF NOT EXISTS `manager_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `data` datetime NOT NULL,
  `managerId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `musculo`
--

CREATE TABLE IF NOT EXISTS `musculo` (
  `musculoId` int(11) NOT NULL AUTO_INCREMENT,
  `musculoFocado` text NOT NULL,
  UNIQUE KEY `musculoId` (`musculoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `newsletter` int(11) NOT NULL,
  `following` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL,
  `notification` text NOT NULL,
  `visto` int(11) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4359 ;

-- --------------------------------------------------------

--
-- Table structure for table `pagamentos`
--

CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contratoId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `dataPagamento` datetime NOT NULL,
  `montante` int(11) NOT NULL,
  `semanas` int(11) NOT NULL,
  `lembrado` int(11) NOT NULL,
  `description` text NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=125 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_code`
--

CREATE TABLE IF NOT EXISTS `page_code` (
  `pageId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  PRIMARY KEY (`pageId`),
  UNIQUE KEY `pageId` (`pageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `paginas`
--

CREATE TABLE IF NOT EXISTS `paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomePagina` text NOT NULL,
  `tipo` text NOT NULL,
  `valor` text NOT NULL,
  `nomeInput` text NOT NULL,
  `idInput` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE IF NOT EXISTS `part` (
  `w` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE IF NOT EXISTS `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `description` text NOT NULL,
  `logo` text NOT NULL,
  `telefone21` int(11) NOT NULL,
  `telefone91` int(11) NOT NULL,
  `telefone96` int(11) NOT NULL,
  `telefone93` int(11) NOT NULL,
  `facebook` text NOT NULL,
  `morada` text NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `active` int(11) NOT NULL,
  `cat` int(11) NOT NULL COMMENT '1- gym , 2-sups, 3-roupa',
  `raio` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `perguntas`
--

CREATE TABLE IF NOT EXISTS `perguntas` (
  `id` int(11) NOT NULL,
  `desc` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plano_alimentar`
--

CREATE TABLE IF NOT EXISTS `plano_alimentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refeicao` int(11) NOT NULL,
  `alimentoId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `progressotreino`
--

CREATE TABLE IF NOT EXISTS `progressotreino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `exercicioId` int(11) NOT NULL,
  `reps1` int(11) NOT NULL,
  `peso` double NOT NULL,
  `series` int(11) NOT NULL,
  `descansoSecs` int(11) NOT NULL,
  `repsT` int(11) NOT NULL,
  `data` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4788 ;

-- --------------------------------------------------------

--
-- Table structure for table `promocao`
--

CREATE TABLE IF NOT EXISTS `promocao` (
  `promoId` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  PRIMARY KEY (`promoId`),
  UNIQUE KEY `promoId` (`promoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `rating_coach`
--

CREATE TABLE IF NOT EXISTS `rating_coach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coachId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `coach_rating` int(11) NOT NULL,
  `recomendation_rating` int(11) NOT NULL,
  `resultados_rating` int(11) NOT NULL,
  `conhecimentos_rating` int(11) NOT NULL,
  `suporte_rating` int(11) NOT NULL,
  `observations` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `receitas_adaptadas`
--

CREATE TABLE IF NOT EXISTS `receitas_adaptadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `recipeId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_alimentos`
--

CREATE TABLE IF NOT EXISTS `recipe_alimentos` (
  `recipeId` int(11) NOT NULL,
  `alimentoId` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=199 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_classification`
--

CREATE TABLE IF NOT EXISTS `recipe_classification` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `receitaId` int(11) NOT NULL,
  `classification` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_extras`
--

CREATE TABLE IF NOT EXISTS `recipe_extras` (
  `recipeId` int(11) NOT NULL,
  `extras` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_macros`
--

CREATE TABLE IF NOT EXISTS `recipe_macros` (
  `recipeId` int(255) NOT NULL,
  `kcals` int(11) NOT NULL,
  `proteina` int(11) NOT NULL,
  `carbs` int(11) NOT NULL,
  `fat` int(11) NOT NULL,
  `fibra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_subcategoria`
--

CREATE TABLE IF NOT EXISTS `recipe_subcategoria` (
  `recipeId` int(11) NOT NULL,
  `subcategoriaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recovery`
--

CREATE TABLE IF NOT EXISTS `recovery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `confirm` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_inicial`
--

CREATE TABLE IF NOT EXISTS `report_inicial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `peso` double NOT NULL,
  `cintura` double NOT NULL,
  `pescoco` double NOT NULL,
  `anca` double NOT NULL,
  `obs` text NOT NULL,
  `data` datetime NOT NULL,
  `activado` int(11) NOT NULL,
  `coachId` int(11) NOT NULL,
  `idade` int(11) NOT NULL,
  `altura` int(11) NOT NULL,
  `sexo` text NOT NULL,
  `objetivo` text NOT NULL,
  `tipo` text NOT NULL,
  `treina` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_semanal`
--

CREATE TABLE IF NOT EXISTS `report_semanal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `peso` double NOT NULL,
  `cintura` double NOT NULL,
  `pescoco` double NOT NULL,
  `anca` double NOT NULL,
  `obs` text NOT NULL,
  `data` datetime NOT NULL,
  `visto` int(11) NOT NULL,
  `coachId` int(11) NOT NULL,
  `idade` int(11) NOT NULL,
  `altura` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=698 ;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE IF NOT EXISTS `shares` (
  `userId` int(11) NOT NULL,
  `postId` text NOT NULL,
  `date` datetime NOT NULL,
  `network` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_gerador`
--

CREATE TABLE IF NOT EXISTS `stats_gerador` (
  `date` datetime NOT NULL,
  `page_code` int(11) NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `region` text NOT NULL,
  `lat` double(10,6) NOT NULL,
  `lng` double(10,6) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_pagina`
--

CREATE TABLE IF NOT EXISTS `stats_pagina` (
  `date` datetime NOT NULL,
  `page_code` text NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `region` text NOT NULL,
  `lat` double(10,6) NOT NULL,
  `lng` double(10,6) NOT NULL,
  `userId` int(11) NOT NULL,
  `ip` text NOT NULL,
  `referer` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_receitas`
--

CREATE TABLE IF NOT EXISTS `stats_receitas` (
  `date` datetime NOT NULL,
  `query` int(11) NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `region` text NOT NULL,
  `lat` double(10,6) NOT NULL,
  `lng` double(10,6) NOT NULL,
  `userId` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats_treino`
--

CREATE TABLE IF NOT EXISTS `stats_treino` (
  `date` datetime NOT NULL,
  `country` text NOT NULL,
  `city` text NOT NULL,
  `region` text NOT NULL,
  `lat` double(10,6) NOT NULL,
  `lng` double(10,6) NOT NULL,
  `userId` int(11) NOT NULL,
  `ip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suplementos`
--

CREATE TABLE IF NOT EXISTS `suplementos` (
  `suplementoId` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `description` text NOT NULL,
  `artigo` text NOT NULL,
  `fase` text NOT NULL,
  `imagem` text NOT NULL,
  PRIMARY KEY (`suplementoId`),
  UNIQUE KEY `idSuplemento` (`suplementoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `a` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7418 ;

-- --------------------------------------------------------

--
-- Table structure for table `treinos`
--

CREATE TABLE IF NOT EXISTS `treinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `obs` text NOT NULL,
  `visto` int(11) NOT NULL,
  `treinoId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=215 ;

-- --------------------------------------------------------

--
-- Table structure for table `treino_esquema`
--

CREATE TABLE IF NOT EXISTS `treino_esquema` (
  `treinoId` int(11) NOT NULL AUTO_INCREMENT,
  `descrição` text NOT NULL,
  `numDias` int(11) NOT NULL,
  `informations` text NOT NULL,
  `coachInfo` text NOT NULL,
  `fase` text NOT NULL,
  PRIMARY KEY (`treinoId`),
  UNIQUE KEY `treinoId` (`treinoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2009 ;

-- --------------------------------------------------------

--
-- Table structure for table `treino_exercicios`
--

CREATE TABLE IF NOT EXISTS `treino_exercicios` (
  `treinoId` int(11) NOT NULL,
  `dia` int(11) NOT NULL,
  `series` int(11) NOT NULL,
  `totalReps` varchar(100) NOT NULL,
  `exercicioId` int(11) NOT NULL,
  `descanso` int(11) NOT NULL,
  `descricao` text NOT NULL COMMENT 'Breve comentário do treino',
  `exId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`exId`),
  UNIQUE KEY `id` (`exId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=440 ;

-- --------------------------------------------------------

--
-- Table structure for table `treino_tipo`
--

CREATE TABLE IF NOT EXISTS `treino_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender` text NOT NULL,
  `times` int(11) NOT NULL,
  `description` text NOT NULL,
  `recomended` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE IF NOT EXISTS `userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `observacoes` text NOT NULL,
  `kcals` int(11) NOT NULL,
  `macros` varchar(40) NOT NULL,
  `protein` int(11) NOT NULL,
  `gorduras` int(11) NOT NULL,
  `carbs` int(11) NOT NULL,
  `treinoId` int(11) NOT NULL,
  `treinoData` datetime NOT NULL,
  `treinoDuracao` int(11) NOT NULL,
  `cardio` text NOT NULL,
  `cardioVezes` int(11) NOT NULL,
  `cardioTempo` int(11) NOT NULL,
  `objetivo` text NOT NULL,
  `coachId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `treinoFim` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=666 ;

-- --------------------------------------------------------

--
-- Table structure for table `usersups`
--

CREATE TABLE IF NOT EXISTS `usersups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `suplementoId` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `quantidade` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `vendas`
--

CREATE TABLE IF NOT EXISTS `vendas` (
  `id` int(11) NOT NULL,
  `seller` int(11) NOT NULL,
  `total_recebido` float(10,6) NOT NULL,
  `custo_compra` float(10,6) NOT NULL,
  `data` date NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `comprador_id` text COLLATE utf8_unicode_ci NOT NULL,
  `comprador_nome` text COLLATE utf8_unicode_ci NOT NULL,
  `comprador_morada` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`user_1`) REFERENCES `0user` (`userId`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`user_2`) REFERENCES `0user` (`userId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
