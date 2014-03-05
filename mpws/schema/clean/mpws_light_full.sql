-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2014 at 02:25 PM
-- Server version: 5.5.35
-- PHP Version: 5.3.10-1ubuntu3.9

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mpws_light`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `getAllShopCategoryBrands`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategoryBrands`(IN catid INT)
BEGIN
  SELECT o.ID,
         o.Name
  FROM   shop_products AS p
         LEFT JOIN shop_origins AS o
                ON p.OriginID = o.ID
  WHERE  p.Status = 'ACTIVE'
         AND o.Status = 'ACTIVE'
         AND p.CategoryID = catid
  GROUP  BY o.Name; 
-- SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.Enabled = 1 AND o.Enabled = 1 AND p.CategoryID = catid GROUP BY o.Name;
END$$

DROP PROCEDURE IF EXISTS `getAllShopCategorySubCategories`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategorySubCategories`(IN catid INT)
BEGIN
  SELECT c.ID,
         c.Name
  FROM   shop_products AS p
         LEFT JOIN shop_categories AS c
                ON p.CategoryID = c.ID
  WHERE  p.Status = 'ACTIVE'
         AND c.Status = 'ACTIVE'
         AND c.ParentID = catid
  GROUP  BY c.Name; 
-- SELECT c.ID, c.ParentID, c.Name FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Enabled = 1 GROUP BY c.Name;
END$$

DROP PROCEDURE IF EXISTS `getShopCategoryBrands`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryBrands`(IN catid INT)
BEGIN
SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.CategoryID = catid GROUP BY o.Name;
END$$

DROP PROCEDURE IF EXISTS `getShopCategoryLocation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryLocation`(IN catid INT)
BEGIN
SELECT T2.ID, T2.Name
FROM (
    SELECT
        @r AS _id,
        (SELECT @r := ParentID FROM shop_categories WHERE ID = _id) AS ParentID,
        @l := @l + 1 AS lvl
    FROM
        (SELECT @r := catid, @l := 0) vars,
        shop_categories h
    WHERE @r <> 0) T1
JOIN shop_categories T2
ON T1._id = T2.ID
ORDER BY T1.lvl DESC;
END$$

DROP PROCEDURE IF EXISTS `getShopCategoryPriceEdges`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryPriceEdges`(IN catid INT)
BEGIN
SELECT MAX( p.Price ) AS 'PriceMax' , MIN( p.price ) AS 'PriceMin' FROM shop_products AS  `p` WHERE p.CategoryID = catid;
END$$

DROP PROCEDURE IF EXISTS `getShopCategorySubCategories`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategorySubCategories`(IN catid INT)
BEGIN
SELECT
  c.ID, c.ParentID, c.Name,
  (SELECT count(*) FROM shop_products AS `p` WHERE p.CategoryID = c.ID AND p.Status = 'ACTIVE') AS `ProductCount`
FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Status = 'ACTIVE' GROUP BY c.Name;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `editor_content`
--

DROP TABLE IF EXISTS `editor_content`;
CREATE TABLE IF NOT EXISTS `editor_content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Property` varchar(100) NOT NULL,
  `Value` text NOT NULL,
  `PageOwner` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `editor_content`
--

INSERT INTO `editor_content` (`ID`, `Property`, `Value`, `PageOwner`) VALUES
(1, 'BUY_READY_ESSAYS', '<p class="c6"><span><b>BUY READY ESSAYS</b></span></p><p class="c3 c6"><span></span></p><p class="c6"><span>In this section you may find an essay that has been previously written on a specific subject that may help you have an idea of what your essay may look like. It is highly recommended that you do not use these essays just as they are for your own purposes, as you can be charged with plagiarism and cause yourself troubles at your educational institution. What you CAN use these essays for is guidance to making an essay of your own. </span></p><p class="c6"><span>How to use these essays specifically? &nbsp;</span></p><p class="c6"><span>For example, you have to write an essay on the “Political and Military limitations of the United States Policies in Vietnam War”. For someone to be able to write a good comprehensive essay on the subject, this person has to have some good background knowledge on the subject to be able to think and write analytically and also do readings of research material relevant to the subject. &nbsp;In the paper we offer you can find the following </span></p><ol class="c0" start="1"><li class="c1"><span class="c5 c4"><font color="#ff0000">Bibliography</font></span><span>&nbsp;that you can consult with, as there is a bibliography with specific references to materials previously located and studied by a writer. In the case of the essay on “the “Political and Military limitations of the United States Policies in Vietnam War” the bibliography would be as follows (in Turabian format ):</span></li></ol><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><span><font size="2">Bibliography<br></font></span><span><font size="2">Asprey, Robert B. War in the Shadows .Garden City: Doubleday, 1975.<br></font></span><span><font size="2">Clausewitz, Carl von . On War. New Jersey: Princeton University Press, 1984<br></font></span><span><font size="2">Giap Vo Nguyen, People''s War, People''s Army .New York: Frederick A. Praeger, Publishers, 1962.<br></font></span><span><font size="2">Hanson Baldwin, “After Vietnam- What Military Strategy in the Far East”, The New York Times, June 9, 1968<br></font></span><span><font size="2">“Johnson assailed on Vietnam War”, The New York Times , August 25, 1965.<br></font></span><span><font size="2">Middleton, Drew.“Lessons of War – Vietnam Spurs a Sweeping Review”, The New York Times, January 29, 1973<br></font></span><span><font size="2">“Picture is cloudy in Vietnam’s war ”, The New York Times, July 28, 1963. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br> </font></span><span><font size="2">Staudenmaier, W.O. Vietnam, Mao and Clausewitz. Parameters, v.VII, no.1 , 79-89., 1977<br></font></span><span><font size="2">“Strategy for Vietnam Peace”, The New York Times, November 12, 1967.<br></font></span><span><font size="2">Tanner, Henry. “DeGaulle Again Attacks US”, The New York Times, January 2, 1967<br></font></span><span><font size="2">“The G.O.P Split Widens”, The New York Times, October 8, 1967.<br></font></span><span><font size="2">Thompson, Robert. Defeating Communist Insurgency, New York: Frederick A. Praeger, Publishers, 1966.<br></font></span><span><font size="2">Weigley, Russell F.. The American Way of War .New York: Macmillan, 1973<br></font></span><span><font size="2">Weinraub, Bernard. “Enemy Strategy in Vietnam - a Puzzle ”,The New York Times , September 23, 1968<br></font></span><span><font size="2">Westmoreland, William C. &nbsp;A Soldier Reports .Garden City: Doubleday, 1976</font></span></p></blockquote><ol class="c0" start="2"><li class="c1"><span class="c5 c4"><font color="#ff0000">Thesis</font> </span><span>has been created by a writer and expanded in the essay. This thesis could be used by you as an example of an argument that you can build for your own essay. It can give you an idea of what the main point is (or one of the main points) and show you the way to work on your own essay. For example, this is an introduction to the mentioned &nbsp;essay theme (the thesis of the essay is marked in bold):</span></li></ol><p class="c2 c3"><span></span></p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><span>The United States'' political and military strategy in Vietnam from 1965-1969 was conducted in such a way that the army as well as civilian authorities never determined and put into action successful approaches to confront the Vietcong National Liberation Front (or NLF). NLF asserted the legitimateness of what a lot of Vietnamese and the USSR considered to be a countrywide war for freedom . As a result, &nbsp;the American politicians could not escape the military conflict’s stalemate because of the limitation of that the Saigon authorities were not able to generate an extensive commitment amid South Vietnam’s progressively war-tired populace. At the same time, the American military could not get the upper hand in the jungle and guerrilla warfare against the Vietcong. </span><span class="c4">It is argued that the American military strategy in Vietnam during 1965-1969 was mistakenly shaped according to the Korean War model and the political strategy failed to make use of international leverages because of the separation of military and political means, which proved to be a critical limitation to winning the war.</span><span>&nbsp; </span></p></blockquote><p class="c2 c3"><span></span></p><p class="c2 c3"><span></span></p><ol class="c0" start="3"><li class="c1"><span class="c4 c5"><font color="#ff0000">Quotes</font></span><span class="c5">&nbsp;</span><span>are often used in the essays we offer that you can use for your own one, but paraphrased and with a proper reference to the source. For example, </span></li></ol><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><span>As soon as the first United States ground military units arrived in South Vietnam in 1965, the setting was created to evaluate the varying U.S.''s and Mao''s understanding of the renowned military thesis suggested Karl von Clausewitz. His doctrine maintained that warfare was the extension of politics by other sorts of methods (Clausewitz,p.87).</span></p><p class="c2"><span>In the bibliography you will find the full reference, which is, in this case :</span></p><p><span>Clausewitz, Carl von . On War. New Jersey: Princeton University Press, 1984</span></p></blockquote><p class="c2 c3"><span></span></p><p class="c2 c3"><span></span></p><ol class="c0" start="4"><li class="c1"><span class="c5 c4"><font color="#ff0000">Arguments</font></span><span>&nbsp;developed in the essays can give you a hint on the arguments you can come up with regard to the specific theme of your essay on this same course. In other words, </span><span class="c5 c4 c9">the structure</span><span class="c5">&nbsp;</span><span>that the writer used can help you understand what kind of structure you could develop for your own essay. </span></li></ol><p class="c2 c3"><span></span></p><blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;"><p><span>Generally speaking, these essays could be used as a good impersonalized consultant to help you with your own assignment.</span></p></blockquote><p class="c2"><a href="#" name="id.8f6dec811e4c"></a></p><p class="c2 c3"><span></span></p><p class="c2"><span>&nbsp; &nbsp;</span></p><p class="c6 c7"><span>&nbsp; &nbsp; </span></p>', 'buy-essays'),
(2, 'WHY_CHOOSE_US', '        \r\n        <h1>Why choose us?</h1>\r\n        <ul>\r\n            <li><strong>An Urgency-oriented team</strong>. Writers with years of experience of working on urgent and super-urgent essays on multiple subjects. The best team for students pressured for time.</li>\r\n            <li><strong>Time-saving management</strong> of your order. BEFORE placing your order you can chat live with the General Manager (Teamlead) or send an email message with questions or requests and enquire of how your order could be or will be processed.</li>\r\n            <li><strong>Direct communication</strong> with the General Manager is provided online, to give you full information on any of your orders, our policies or refund decisions.</li>\r\n            <li><strong>Low market prices</strong></li>\r\n            <li>All procedures are <strong>confidential and secured</strong> by a Money back guarantee.</li>\r\n            <li><strong>FREE personal account</strong> ("My Writing desk") to handle and trace all your orders and GET SPECIAL DISCOUNTS. Check Current discount</li>\r\n        </ul>\r\n                ', 'home'),
(3, 'TERMS_AND_CONDITIONS', '<p style="font-family: ''Times New Roman''; font-size: medium; ">Custom essay writing services offer essays,research papers, term papers, book reports and dissertations on various themes to help students with their individual writing goals. Customized research paper writing services hence offer expert aid in generating your personal essays, book reports and term papers by delivering an original view on yourtheme, more comprehensive research, etc.</p><p style="font-family: ''Times New Roman''; font-size: medium; ">The only thing you do is to complete and submit the order form. After that continue with the payment. You need to complete and submit the payment form to finish the transaction.</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Presently, we take all popular credit cards: Visa, MasterCard, Discover and American Express .Our custom papers will be delivered by way of e-mail for no additional charge. You will get your work attached to the letter in MS Word (.doc).We provide the work within the chosen delivery interval ( 8, 12 ,24 , 48 hours, etc); nonetheless, we are not accountable for setbacks in delivery (up to three hours) induced by technical problems .</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Our service provides refunds if the paper doesnt not objectively follow the initial details of the order. Change of details may require change of the price. Revisions are free.No revision and no refund requests are processed after two weeks since the order delivery.</p>\r\n                ', 'terms'),
(4, 'PRICES', '<table cellpadding="5" cellspacing="0" border="0" width="100%">\r\n<tr>\r\n<td class="cell01">Price per page<br />(approx. 300 words)</td>\r\n<td class="cell01">8+ days</td>\r\n<td class="cell01">6-7 days</td>\r\n<td class="cell01">4-5 days</td>\r\n<td class="cell01">3 days</td>\r\n<td class="cell01">2 days</td>\r\n<td class="cell01">24 hours</td>\r\n<td class="cell01">12 hours</td>\r\n</tr>\r\n<tr>\r\n<td class="cell01">High School</td>\r\n<td class="cell02">$14</td>\r\n<td class="cell02">$16</td>\r\n<td class="cell02">$18</td>\r\n<td class="cell02">$19</td>\r\n<td class="cell02">$23</td>\r\n<td class="cell02">$27</td>\r\n<td class="cell02">$32</td>\r\n</tr>\r\n<tr>\r\n<td class="cell01">College</td>\r\n<td class="cell02a">$17</td>\r\n<td class="cell02a">$19</td>\r\n<td class="cell02a">$21</td>\r\n<td class="cell02a">$23</td>\r\n<td class="cell02a">$26</td>\r\n<td class="cell02a">$30</td>\r\n<td class="cell02a">$35</td>\r\n</tr>\r\n<tr>\r\n<td class="cell01">University</td>\r\n<td class="cell02">$20</td>\r\n<td class="cell02">$23</td>\r\n<td class="cell02">$25</td>\r\n<td class="cell02">$27</td>\r\n<td class="cell02">$29</td>\r\n<td class="cell02">$34</td>\r\n<td class="cell02">$40</td>\r\n</tr>\r\n<tr>\r\n<td class="cell01">Masters/MBA</td>\r\n<td class="cell02a">$24</td>\r\n<td class="cell02a">$27</td>\r\n<td class="cell02a">$30</td>\r\n<td class="cell02a">$34</td>\r\n<td class="cell02a">$36</td>\r\n<td class="cell02a">$40</td>\r\n<td class="cell02a">$44</td>\r\n</tr>\r\n<tr>\r\n<td class="cell01">PhD</td>\r\n<td class="cell02">$27</td>\r\n<td class="cell02">$30</td>\r\n<td class="cell02">$34</td>\r\n<td class="cell02">$36</td>\r\n<td class="cell02">$38</td>\r\n<td class="cell02">$42</td>\r\n<td class="cell02">$48</td>\r\n</tr>\r\n</table>', 'prices'),
(5, 'FAQ', '\r\n        \r\n        \r\n        \r\n        \r\n        \r\n        <p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">1. What are your credentials?</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Our authors are university graduates and genuine experts. Practically all are well trained and can boast years of customized writing behind them.</p><p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; "><u><i>2. What sorts of essays as well as research papers do you create ?</i></u></p><p style="font-family: ''Times New Roman''; font-size: medium; ">Simply all sorts! Art,Biology, Accounting, Drama, Chemistry,Business,Zoology, - it could be anything. There is not a single issue that our writers are not able to deal with.</p><p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">3. Are your custom essays genuinely custom-made?</p><p style="font-family: ''Times New Roman''; font-size: medium; ">We wish to emphasize that all of our custom works are created manually in accordance with your directions. Customer`s approval is our primary objective.</p><p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">4. Suppose I am not totally pleased with the custom essay I have just gotten?</p><p style="font-family: ''Times New Roman''; font-size: medium; ">We do provide cost-free revisions until your custom work perfectly satisfies your requirements. Make sure you have provided all instructions and precise demands ( paper format, sources, writing style , citations, and so on) in the order information area as you complete the order form.</p><p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">5. What is your privacy policy?</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Personal privacy as well as security is among our greatest concerns. We provide total privacy and have developed 100 percent secured procedure for order acquiring and credit card handling . We will never expose a customer''s order information,identity, or e-mail address contact information to virtually any third party. We honor and support the privacy of all our customers.</p><p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">6. Is your internet site secure?</p><p style="font-family: ''Times New Roman''; font-size: medium; ">All of our online transactions are handled by 2checkout.com, a company which offers a safe and reputable internet payment method. With large numbers of members worldwide, 2checkout.com is proud to be Payment Card Industry (PCI) level 1(highest) Data Security Standard.</p>\r\n                                                                ', 'faq'),
(6, 'REVIEWS', '<p class="cell06" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Read the reviews and learn more about how you can use our service for your greatest benefit!</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Communication is one of the basic conditions for a good custom made essay. Essay writing may require additional question and answer sessions, since order details may not always be clear enough for a successful writer''s work. For this reason our customers feel free to communicate with the writers by means of messaging and, in particular cases, with the Teamlead to best suit the given writer''s work to the purposes of the customer.</p><p style="font-family: ''Times New Roman''; font-size: medium; ">This writing service consists of a team of people who are, first of all, not some essay writing machines, but people who value integrity, and that is why below we provide some bits of actual correspondence ( POSITIVE, NEGATIVE and MIXED reviews) between a writer and a customer to help you get a better picture of what our writing service is about. As you may remember from the front page information, we are an essay writing service that particularly focuses on urgent (less than 24 hours to deadline) and super-urgent orders (less than 12 hours) irrespective of the amount of pages. Therefore, the reviews shown below were given in response to works written exclusively within those categories of orders and, thus, show our level of readiness to react fast to your urgencies.</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Examples of POSITIVE Reviews</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">This paper is exactly what I was hoping for. This is what I have been reading about but had no time to put it together. This work is fantastic. I wish I could utilize your knowledge and dedication in the future. Is there any way I can request your services in the future, and what other subjects do you cover?</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Best regards MJ, September , 2011</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Writer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">I cover basically any kind of subject, depending on the complexity of the task. You can ask for my ID from the team lead and I will be happy to help you again.</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Thanks for the quick response, you will hear from me soon.thanks again for the great work.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">MJ, September , 2011</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Some customers get to be really impressed by our fast and effective management of their orders:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">I wanted to extend my gratitude to the write who have written my paper, although it was short notice, you have done a good job...... thank you very much I truly appreciate it...</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">SW, November, 2011</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Some customers treat our work as more than just another ordered and delivered quality work – they learn themselves on the basis of how essays are written:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Thank you so much for the paper you wrote. I used it as a model to work from. It was very helpful. Thank you so much. :)</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">JT, December, 2011</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Some customers build a good friendship with the writers they prefer</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Thank you so much for this.Love you!</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">JT, December, 2011</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">U are perfect man. Do it. Thanks a lot!</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">OS, January, 2012</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">At times our essay writers get to talk even to the PARENTS of the students who regularly order essays at our writing service:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">I was so touched by how personal one could make another one''s paper. I cried as I read this essay.....I am the mother. I am sure that my daughter will send you a message too. She told me that you did an amazing job. Unfortunately, I was not able to help her, and her load is ''huge'' at this time. Thank you so very much! Wishing you many blessings! ...the Mom.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">VM, January, 2012</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Writer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">You are very welcome! If there anything else I could do for you, feel free to ask.</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Here is a case of how communication between a customer and a writer can help better identify the real purpose of the custom essay to be written:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">I loved the paper you wrote, however I guess I wasn''t clear enough about what the paper needed to include. The fieldwork experience part was great, still I needed to write about how I have connected children and science through what I have learned in my science education class, for example we''ve read articles, have done hand''s on activities, mood observation, we''re reading, "Primary Science," ets and through our readings I''ve learned to embrace and not dismiss any of the ideas of children, to avoid getting them discouraged etc. Would it be possible to add on to the paper, this part is crucial to my grade, and If you need more information please feel free to ask me.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">JD, February, 2012</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Some customers treat the work done by our writers not as something they will not dare change, but as a basis for their subsequent personal editing and tailoring exactly to their needs:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">You provided a decent base for the paper. Thank you for providing the base for my paper, because I''m extremely busy. I appreciate it.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">PC, February, 2012</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Some customers get additional FREE consultation from our writers with respect to the completed essays and can use it for enhancing their custom made essays'' depth of thought.</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">The essay seems to answer"What kind of person is Tiberius "instead of "What motivated Tiberius".Please revise it asap. WH, March, 2012</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Writer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Hello again!<br>Concernig the motivation. You must understand, that the formula of his motivation is not a simple one-variable thing. Even the sources you sent confirm that it cannot be clearly and confidently stated. His formula for motivation consisted of ,as argued in the essay, not just personal charisma, not just altruism, not just patriotism, but all of them together.<br>As a patriot, he wanted the better future for his country, as an altruist, he wanted the poor have means for living, as a leader,he was ready to go to the extremes to achive his goal.&nbsp;<br>This is what the essay has to answer to the title''s question, as could be found in the conclusion "Summing up, one could say that Tiberius Gracchus did not target some sort of personal enrichment or nowhere showed the longing for his own personality cult, but being engulfed with his charismatic</p><p style="font-family: ''Times New Roman''; font-size: medium; ">vision to change the destructive order to help the common people, he soon turned into a ready-for-all revolutionary, with the whole of his life put at stake for the implementation of his vision".<br>I hope this helps you. Btw, you can use the above ideas as you want.<br>Hope, we have finally settled it this time.</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Examples of MIXED Reviews:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Very Good, just a few grammatical error. too much background, however, as it is an analysis of the painting. What is your writer #? I would like to use you for my next paper.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">JJ, March, 2012</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Good control in grammer, and the sentence structure is good enough for college student''s level. one of the most important things is that the writer finish it on time. if he finish it late, teachers will not accept the paper anymore. the only thing i don''t like is some sentences are copied, not in the writer''s own words.</p><p class="cell04" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">BW, March, 2012</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Writer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Thanks for the quick response. There is no copy paste in the work. As its a subject of history, the same names of the events, same dates, names of people,same phrases will recur in writing of different people and make them similar .Likewise, i have provided ample references to the sources from which the materials were taken. And of course, did a fast job of reading and writing on their basis. Thank you again for your quick and substantive feedback!</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Examples of NEGATIVE Reviews:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">As you will understand from the conversation below, a two-way communication while placing an order and during the time when an essay is being written is a major factor in satisfying a customers'' need:</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Customer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">i dont need my paper to be redone. it is already graded and cannot be redone. i gave you the exact grading point system that my instuctor used to grade the paper and i still recieved a 61%. this is unacceptable. i paid $105 for a quality paper to be writen because i needed the good grade. one of your garuntees amongst the many of them is that the paper will be writen by an expert in the field on the subject they are writing about. REALLLY??<br>Also, you guarntee 100% that the paper will be writen with the specifications that are given. I gave you the EXACT specifications striaght what the instuctor gave me. you had the assignment, the EXACT grading point system that the instructor uses and still my paper is basically an F. Is this truely acceptable for your company? is this what you pride your high standards and guarantees of quality off of? I had planned on having two other papers writen with you, but with this type of work you do im glad that i didnt move forward with that.I live in a very large college district and i intend on spreading the word about your services to all that i can. Also, i demand the 100% money back guarantee that you have plastered all over your websiite immediatly. i will provide you with a copy of the paper that i recieved from your company graded if you need me to. just let me know where you would like it sent.</p><p class="cell02" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; text-align: -webkit-auto; font-size: medium; ">Writer:</p><p style="font-family: ''Times New Roman''; font-size: medium; ">Dear client!<br>We are sorry to hear how your professor has scored your paper.We understand your frustration. Let us remind you of "the exact grading point system" that you refer to in you letter. "must be unique, one subject being observed. instuctor very strict on formatting and citation. gave me a 0 on last paper because of it. text book used for this class is Discovering The Lifespan(1st edition) Robert Feldman, Pearson Prentice Hall, 2009 ISBN#978-0136-061-670 Use for citing ref." From this we can extract the following "exact grading point system":<br>1)must have no matches with other works<br>2)APA format must be adhered<br>3)critically important to use the given book<br>4)citaions must be provided from the given book.<br>Let us discuss it point by point.<br>1) it is of no difficulty to paste the work in http://www.articlechecker.com, a tool used to check plagiarism and see that it shows "no matches found".<br>2) apa format is observed,as the your paper was done in an APA template, freely dowloaded from most athoritative educational websites<br>3)the writer sent you as many as three messages trying to get in contact with you and you replied to none of them. the writer''s repsonse was the critical need of the link to the book you specified. the writer spent a lot of time searching the Internet and found no link. Yet, as you did not reply, and the writer trying to help you as best as possible, began to search for indirect mentions of the book in the net and found some. The writer informed you of all the actions taken and you did not respond in any way. Your reasons for not checking your mail is beyond the writer''s responsibility. The writer showed persistence in attempting to serve the unresponding client in whatever way it was possible, with only opportuntity left as using sites quoting the book indirectly.Yet the quotes were made direct in your paper, as it was demanded so. This takes care of point #4.<br><br>No other demands or criteria of the "exact grading point system" were available to the writer (none more concerning the structure or the content)and we should thank the writer for the persistence to serve the client the best way possible. Unfortunatley, you appeared not to respond to all the informed subsequent steps the writer took to help you, seeing the need of your guidance to make work even better.<br><br>Again, we do share your frustration of the grade you received.Your professor must have his\\her educational reasons for that and this must be accepted. At the same time we see that the author tried his best to help you in all ways available.</p><p class="cell03" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-weight: normal; text-align: -webkit-auto; font-size: medium; ">Be sure to communicate your order details clearly and comprehensively!</p>\r\n                ', 'testemonials');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_accountAddresses`
--

DROP TABLE IF EXISTS `mpws_accountAddresses`;
CREATE TABLE IF NOT EXISTS `mpws_accountAddresses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) DEFAULT NULL,
  `Address` varchar(500) NOT NULL,
  `POBox` varchar(50) NOT NULL,
  `Country` varchar(300) NOT NULL,
  `City` varchar(300) NOT NULL,
  `Status` enum('ACTIVE','REMOVED') NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AccountID` (`AccountID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `mpws_accountAddresses`
--

INSERT INTO `mpws_accountAddresses` (`ID`, `AccountID`, `Address`, `POBox`, `Country`, `City`, `Status`, `DateCreated`, `DateUpdated`) VALUES
(5, 79, 'wwwww', 'fsdfsdf', 'rererererer', 'fdsfsdfsdf', 'REMOVED', '2014-03-02 20:26:25', '2014-03-02 22:37:12'),
(19, 79, 'fsdfsdf', '345345345', 'fsdfsdf', 'uuuuuuu', 'REMOVED', '2014-03-02 22:47:47', '2014-03-02 22:55:48'),
(20, 79, 'dsadfasdasdfdfdsfdsf', 'fffff', 'ffff', 'dasdasdasdasd', 'REMOVED', '2014-03-02 23:02:38', '2014-03-03 00:40:52'),
(21, 79, 'setsertser', 'tsetest', 'dfsdfsdfsd', 'fsdfsdfsdfsd', 'REMOVED', '2014-03-03 00:40:27', '2014-03-03 00:40:53'),
(22, 79, 'Horodotska 123', '79001', 'Ukraine', 'Lviv', 'ACTIVE', '2014-03-03 00:41:05', '2014-03-03 19:09:57'),
(23, 79, 'xxxxxx', 'xxxxxx', 'xxxxxx', 'xxxxxx', 'REMOVED', '2014-03-03 01:00:12', '2014-03-03 15:04:04'),
(24, 79, 'Lvivska 34', '57841', 'Ukraine', 'Kyiv', 'ACTIVE', '2014-03-03 19:10:39', '2014-03-04 01:51:16'),
(25, 79, 'Kyivska 3', '78451', 'Ukraine', 'Rivne', 'ACTIVE', '2014-03-04 02:22:33', '2014-03-04 02:25:13'),
(26, NULL, 'demo', '120012', 'Ukraine', 'Lviv', 'ACTIVE', '2014-03-04 02:43:40', '2014-03-04 02:43:40'),
(27, NULL, 'demo', '120012', 'Ukraine', 'Lviv', 'ACTIVE', '2014-03-04 02:43:46', '2014-03-04 02:43:46'),
(28, NULL, 'demo', '120012', 'Ukraine', 'Lviv', 'ACTIVE', '2014-03-04 02:46:43', '2014-03-04 02:46:43');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_accounts`
--

DROP TABLE IF EXISTS `mpws_accounts`;
CREATE TABLE IF NOT EXISTS `mpws_accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `IsTemporary` tinyint(1) NOT NULL DEFAULT '1',
  `FirstName` varchar(200) COLLATE utf8_bin NOT NULL,
  `LastName` varchar(200) COLLATE utf8_bin NOT NULL,
  `EMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `Phone` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin NOT NULL,
  `ValidationString` varchar(400) COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `EMail` (`EMail`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=82 ;

--
-- Dumping data for table `mpws_accounts`
--

INSERT INTO `mpws_accounts` (`ID`, `CustomerID`, `IsTemporary`, `FirstName`, `LastName`, `EMail`, `Phone`, `Password`, `ValidationString`, `Status`, `DateCreated`, `DateUpdated`) VALUES
(77, 1, 1, 'James', 'Griffin', 'test@test.com', NULL, '24d04aa3d61423fb9dae48ac4d7567d5', '72e6d9fe68147c4feb0cf7b035be9e05', 'ACTIVE', '2014-03-01 01:14:14', '2014-03-01 01:14:14'),
(78, 1, 0, 'James', 'demo2', 'test@demo2.com', NULL, '24d04aa3d61423fb9dae48ac4d7567d5', 'c261191eb31433b98994f58e57567a67', 'ACTIVE', '2014-03-01 01:16:39', '2014-03-01 01:16:39'),
(79, 1, 0, 'Test', 'Demo', 'demo@demo.com5', '097-56-56-201', '24d04aa3d61423fb9dae48ac4d7567d5', 'b74c7e4ec4dc62728ee5a2195a8605b2', 'ACTIVE', '2014-03-01 14:38:46', '2014-03-04 14:42:02'),
(80, 1, 1, 'tset', '', 'tset', 'ttset', '4a123a551c46b3a7a2e1b6b76e7d69c9', 'f5046014417bd9c1098e0f29bd5abf59', 'ACTIVE', '2014-03-01 14:45:03', '2014-03-01 14:45:03'),
(81, 1, 1, '', '', '', '', '4a123a551c46b3a7a2e1b6b76e7d69c9', '23fcad34643fa6b3c4d9765778498f21', 'ACTIVE', '2014-03-01 14:45:14', '2014-03-01 14:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_customer`
--

DROP TABLE IF EXISTS `mpws_customer`;
CREATE TABLE IF NOT EXISTS `mpws_customer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ExternalKey` varchar(100) COLLATE utf8_bin NOT NULL,
  `Name` text COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mpws_customer`
--

INSERT INTO `mpws_customer` (`ID`, `ExternalKey`, `Name`, `Status`, `HomePage`, `DateCreated`, `DateUpdated`) VALUES
(0, 'toolbox', 'toolbox', 'ACTIVE', '', '2013-09-03 00:00:00', '2013-09-03 00:00:00'),
(1, 'pb_com_ua', 'Pobutteh', 'ACTIVE', '', '2013-09-03 00:00:00', '2013-09-03 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_jobs`
--

DROP TABLE IF EXISTS `mpws_jobs`;
CREATE TABLE IF NOT EXISTS `mpws_jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Action` text COLLATE utf8_bin NOT NULL,
  `Schedule` datetime NOT NULL,
  `LastError` text COLLATE utf8_bin,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mpws_jobs`
--

INSERT INTO `mpws_jobs` (`ID`, `CustomerID`, `Name`, `Description`, `Action`, `Schedule`, `LastError`, `DateUpdated`, `DateCreated`) VALUES
(6, 0, 'SI Team Render Weekly Report', 'SI Team Render Weekly Report', 'http://toolbox.mpws.com/api.js?caller=reporting&fn=Render&p=token=656c88543646e400eb581f6921b83238&realm=plugin&oid=1&name=SI Team Render Weekly Report&custom=script%3Dweekly%26schedule%3D%2A%2F30+%2F1+%2A+%2A+%2A+%2A', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', '2013-05-16 22:50:23'),
(7, 0, 'GenerateSitemap', 'GenerateSitemap', 'http://toolbox.mpws.com/api.js?caller=shop&fn=ShopActionTrigger&p=token=656c88543646e400eb581f6921b83238&realm=plugin&oid=5&name=GenerateSitemap&custom=schedule%3D%2A+%2F1+%2A+%2A+%2A+%2A', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', '2013-08-18 20:14:13');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_subscripers`
--

DROP TABLE IF EXISTS `mpws_subscripers`;
CREATE TABLE IF NOT EXISTS `mpws_subscripers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `ContentType` enum('NEWSLETTER','OFFERS') COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_uploads`
--

DROP TABLE IF EXISTS `mpws_uploads`;
CREATE TABLE IF NOT EXISTS `mpws_uploads` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Path` text CHARACTER SET latin1 NOT NULL,
  `Owner` text CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mpws_uploads`
--

INSERT INTO `mpws_uploads` (`ID`, `CustomerID`, `Path`, `Owner`, `Description`, `DateCreated`) VALUES
(3, 0, '/var/www/mpws/rc_1.0/data/uploads/2012-08-26/f03a4a9c48b90e54b99f84014dcdb787/e-card.PNG', 'f03a4a9c48b90e54b99f84014dcdb787', NULL, '2012-08-26 22:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `mpws_users`
--

DROP TABLE IF EXISTS `mpws_users`;
CREATE TABLE IF NOT EXISTS `mpws_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Password` varchar(32) CHARACTER SET latin1 NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `Permisions` text CHARACTER SET latin1 NOT NULL,
  `Role` enum('SUPERADMIN','READER','REPORTER') CHARACTER SET latin1 NOT NULL DEFAULT 'READER',
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=29 ;

--
-- Dumping data for table `mpws_users`
--

INSERT INTO `mpws_users` (`ID`, `CustomerID`, `Name`, `Password`, `Active`, `IsOnline`, `Permisions`, `Role`, `DateLastAccess`, `DateCreated`, `DateUpdated`) VALUES
(1, 0, 'TestUser', '', 0, 0, 'Toolbox:*:all;\\r\\nWriter:*:all;', 'READER', '2012-06-26 00:00:00', '0000-00-00 00:00:00', '2012-10-27 16:55:54'),
(3, 0, 'test3', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 1, '', 'READER', '2013-10-23 00:30:17', '2012-06-25 23:56:20', '0000-00-00 00:00:00'),
(4, 0, 'TestUser', 'fe01ce2a7fbac8fafaed7c982a04e229', 0, 0, 'Toolbox:*:all;\r\nWriter:*:all;', 'READER', '2012-06-26 00:00:00', '2012-06-26 00:00:00', '0000-00-00 00:00:00'),
(25, 0, 'testusersecond', '84730c4bf2fac85a5a74e9722eb88f5d', 1, 0, '*.*', 'SUPERADMIN', '0000-00-00 00:00:00', '2012-10-27 16:53:48', '2012-10-27 16:53:48'),
(26, 0, 'TestUserAAAA', '', 0, 0, 'qqq', 'SUPERADMIN', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2012-10-29 23:20:12'),
(28, 0, 'aaaaaaaaaaaaaaaaaaa', '84730c4bf2fac85a5a74e9722eb88f5d', 0, 0, 'fgdgdfgdgfdg', 'SUPERADMIN', '0000-00-00 00:00:00', '2012-10-29 23:55:18', '2012-10-29 23:55:18');

-- --------------------------------------------------------

--
-- Table structure for table `reporting_all`
--

DROP TABLE IF EXISTS `reporting_all`;
CREATE TABLE IF NOT EXISTS `reporting_all` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `ExternalKey` text NOT NULL,
  `DataPath` varchar(150) NOT NULL,
  `ReportDataUrl` varchar(250) DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `reporting_all`
--

INSERT INTO `reporting_all` (`ID`, `Name`, `ExternalKey`, `DataPath`, `ReportDataUrl`, `DateCreated`, `DateUpdated`) VALUES
(1, 'Strategic Integration Team', 'strategic-integration-team', '/var/www/mpws/rc_1.0/data/custom/reporting/strategic-integration-team', 'weekly;release;', '0000-00-00 00:00:00', '2012-10-28 13:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `reviews_reviews`
--

DROP TABLE IF EXISTS `reviews_reviews`;
CREATE TABLE IF NOT EXISTS `reviews_reviews` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OriginIP` varchar(20) NOT NULL,
  `OriginDomain` varchar(50) NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `ReviewerID` int(11) NOT NULL,
  `Nickname` text NOT NULL,
  `WouldRecommend` tinyint(1) NOT NULL,
  `Title` text NOT NULL,
  `ReviewText` text NOT NULL,
  `Rating` double NOT NULL,
  `Pros` text,
  `Cons` text,
  `ModerationStatus` enum('SUBMITTED','APPROVED','REJECTED') NOT NULL DEFAULT 'SUBMITTED',
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_boughts`
--

DROP TABLE IF EXISTS `shop_boughts`;
CREATE TABLE IF NOT EXISTS `shop_boughts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductPrice` decimal(10,0) NOT NULL,
  `Quantity` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `shop_boughts`
--

INSERT INTO `shop_boughts` (`ID`, `ProductID`, `OrderID`, `ProductPrice`, `Quantity`) VALUES
(20, 5, 19, 17, 1),
(21, 5, 20, 17, 1),
(22, 3, 20, 213, 1),
(23, 3, 21, 213, 1),
(24, 4, 21, 100, 10),
(25, 4, 24, 100, 7),
(26, 4, 27, 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
CREATE TABLE IF NOT EXISTS `shop_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RootID` int(11) DEFAULT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `CustomerID` int(11) NOT NULL,
  `SchemaID` int(11) DEFAULT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `RootID` (`RootID`),
  KEY `ParentID` (`ParentID`),
  KEY `SchemaID` (`SchemaID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

--
-- Dumping data for table `shop_categories`
--

INSERT INTO `shop_categories` (`ID`, `RootID`, `ParentID`, `CustomerID`, `SchemaID`, `ExternalKey`, `Name`, `Description`, `Status`, `DateCreated`, `DateUpdated`) VALUES
(1, NULL, NULL, 1, NULL, '', 'Побутова техніка', 'Побутова техніка', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(2, 1, 1, 1, NULL, '', 'Дошка прасувальні', 'Дошка прасувальні', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(3, NULL, NULL, 1, NULL, '', 'Мийка високого тиску', 'Мийка високого тиску', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(4, 1, 1, 1, NULL, '', 'Посуд', 'Посуд', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(5, NULL, NULL, 1, NULL, '', 'Професійна техніка', 'Професійна техніка', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(6, NULL, NULL, 1, 1, '', 'ТВ, відео, аудіо, фото', 'ТВ, відео, аудіо, фото', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(7, 6, 6, 1, NULL, '', 'Телевізори', 'Відео обладнання', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(12, 6, 7, 1, NULL, 'lct_televizoru', 'LCD телевізори', 'LCD телевізори', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 1, 1, 1, NULL, 'kt', 'Кліматична техніка', 'Кліматична техніка', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 1, 1, 1, NULL, 'kt', 'Крупна побутова техніка', 'Крупна побутова техніка', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 1, 1, 1, NULL, 'kt', 'Дрібна побутова техніка', 'Дрібна побутова техніка', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 1, 1, 1, NULL, 'kt', 'Догляд за будинком', 'Догляд за будинком', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 6, 6, 1, NULL, 'kt', 'Аудіо', 'Аудіо', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 6, 6, 1, NULL, 'kt', 'Відео', 'Відео', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 6, 6, 1, NULL, 'kt', 'Фото', 'Фото', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 6, 6, 1, NULL, 'kt', 'Ігрові приставки', 'Ігрові приставки', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, NULL, NULL, 1, NULL, 'kt', 'Авто товари', 'Авто товари', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 21, 21, 1, NULL, 'kt', 'Автоелектроніка', 'Автоелектроніка', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 21, 21, 1, NULL, 'kt', 'Авто звук', 'Авто звук', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 21, 23, 1, NULL, 'kt', 'Автомагнітоли', 'Автомагнітоли', 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 21, 23, 1, NULL, '', 'Аксесуари до автозвуку', 'Аксесуари до автозвуку', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(26, 21, 21, 1, NULL, '', 'АвтоОптика (Світло)', 'АвтоОптика (Світло)', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07'),
(27, 21, 26, 1, NULL, '', 'Габаритні вогні', 'Габаритні вогні', 'ACTIVE', '2013-08-27 02:26:07', '2013-08-27 02:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `shop_commands`
--

DROP TABLE IF EXISTS `shop_commands`;
CREATE TABLE IF NOT EXISTS `shop_commands` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` text CHARACTER SET latin1 NOT NULL,
  `Name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1 NOT NULL,
  `Action` text CHARACTER SET latin1 NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Name` (`Name`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Dumping data for table `shop_commands`
--

INSERT INTO `shop_commands` (`ID`, `CustomerID`, `ExternalKey`, `Name`, `Description`, `Action`, `DateCreated`, `DateUpdated`, `DateLastAccess`) VALUES
(4, 0, 'import-descriptions', 'import descriptions', 'import descriptions', 'import descriptions', '2013-08-18 19:23:10', '2013-08-18 19:23:10', '0000-00-00 00:00:00'),
(5, 0, 'generate-sitemap', 'generate sitemap', 'generate sitemap', 'generate sitemap', '2013-08-18 20:09:25', '2013-08-18 20:09:25', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shop_currency`
--

DROP TABLE IF EXISTS `shop_currency`;
CREATE TABLE IF NOT EXISTS `shop_currency` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `IsMain` tinyint(1) NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `Currency` varchar(10) COLLATE utf8_bin NOT NULL,
  `Rate` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`),
  KEY `Currency` (`Currency`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shop_currency`
--

INSERT INTO `shop_currency` (`ID`, `CustomerID`, `IsMain`, `Status`, `Currency`, `Rate`, `DateCreated`, `DateUpdated`, `DateLastAccess`) VALUES
(1, 0, 0, 'ACTIVE', 'EUR', 10.70, '0000-00-00 00:00:00', '2013-08-15 00:36:46', '0000-00-00 00:00:00'),
(2, 0, 0, 'ACTIVE', 'USD', 8.10, '0000-00-00 00:00:00', '2013-08-15 00:36:53', '0000-00-00 00:00:00'),
(3, 0, 1, 'ACTIVE', 'UAH', 1.00, '2013-08-15 00:37:14', '2013-08-15 00:37:14', '0000-00-00 00:00:00'),
(4, 0, 0, 'ACTIVE', 'PLN', 2.52, '2013-08-17 01:30:40', '2013-08-17 01:30:40', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shop_offers`
--

DROP TABLE IF EXISTS `shop_offers`;
CREATE TABLE IF NOT EXISTS `shop_offers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `Type` enum('SHOP_CLEARANCE','SHOP_NEW','SHOP_HOTOFFER','SHOP_BESTSELLER','SHOP_LIMITED') COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateActive` datetime NOT NULL,
  `DateInactive` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
CREATE TABLE IF NOT EXISTS `shop_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) DEFAULT NULL,
  `AccountAddressesID` int(11) NOT NULL,
  `Shipping` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Warehouse` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Comment` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','SHOP_REVIEWING','SHOP_PACKAGE','LOGISTIC_DELIVERING','CUSTOMER_POSTPONE','CUSTOMER_CANCELED','CUSTOMER_CHANGED','SHOP_WAITING_CUSTOMER_APPROVAL','CUSTOMER_APPROVED','LOGISTIC_DELIVERED','SHOP_CLOSED','CUSTOMER_REOPENED','CUSTOMER_CLOSED','CUSTOMER_WAITNG_REFUND','SHOP_REFUNDED','REMOVED','NEW') COLLATE utf8_bin NOT NULL DEFAULT 'NEW',
  `Hash` varchar(100) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `Hash` (`Hash`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

--
-- Dumping data for table `shop_orders`
--

INSERT INTO `shop_orders` (`ID`, `CustomerID`, `AccountID`, `AccountAddressesID`, `Shipping`, `Warehouse`, `Comment`, `Status`, `Hash`, `DateCreated`, `DateUpdated`) VALUES
(19, 1, 79, 0, 'company_gunsel', 'test', 'test', 'NEW', 'feceb946892d6553534402fe85d34b0f', '2014-03-01 14:38:46', '2014-03-01 14:38:46'),
(20, 1, 79, 0, 'company_novaposhta', 'tset', 'setset', 'LOGISTIC_DELIVERED', '4a45f03d9c345814c22c3ba14977040f', '2014-03-01 14:45:03', '2014-03-01 14:45:03'),
(21, 1, 79, 0, 'self', '', '', 'SHOP_CLOSED', 'bf62eae73bb93385458205350b41f5c8', '2014-03-01 14:45:15', '2014-03-01 14:45:15'),
(22, 1, 79, 26, 'self', '12', 'call', 'NEW', '539c1281990325f8870ee236920231ca', '2014-03-04 02:43:40', '2014-03-04 02:43:40'),
(23, 1, 79, 27, 'self', '12', 'call', 'NEW', '594b40d6834fe6b7c1988e022f0e5833', '2014-03-04 02:43:46', '2014-03-04 02:43:46'),
(24, 1, 79, 28, 'self', '12', 'call', 'NEW', '3bcff429fdb62932c9ff7636461b74e7', '2014-03-04 02:46:43', '2014-03-04 02:46:43'),
(25, 1, 79, 22, 'self', '12', '444', 'NEW', '91bac37fd7056ede8da053fae4164d71', '2014-03-04 02:52:48', '2014-03-04 02:52:48'),
(26, 1, 79, 22, 'self', '12', '444', 'NEW', '91a92b3864b2053b89d9214aa56f5f0d', '2014-03-04 02:53:27', '2014-03-04 02:53:27'),
(27, 1, 79, 24, '', '', 'dedededede', 'NEW', '1206292bb1b863c76096e2e37d73232b', '2014-03-04 03:02:31', '2014-03-04 03:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `shop_origins`
--

DROP TABLE IF EXISTS `shop_origins`;
CREATE TABLE IF NOT EXISTS `shop_origins` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

--
-- Dumping data for table `shop_origins`
--

INSERT INTO `shop_origins` (`ID`, `CustomerID`, `ExternalKey`, `Name`, `Description`, `HomePage`, `Status`, `DateCreated`, `DateUpdated`) VALUES
(1, 1, '', 'SONY', 'SONY', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(2, 1, '', 'DELL', 'DELL', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(3, 1, '', 'HP', 'HP', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(4, 1, '', 'Samsung', 'Samsung', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(5, 1, '', 'LG', 'LG', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(6, 1, '', 'Toshiba', 'Toshiba', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(7, 1, '', 'SHARP', 'SHARP', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41'),
(8, 1, '', 'Apple', 'Apple', 'http://www.sony.com', 'ACTIVE', '2013-08-27 02:26:41', '2013-08-27 02:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `shop_productAttributes`
--

DROP TABLE IF EXISTS `shop_productAttributes`;
CREATE TABLE IF NOT EXISTS `shop_productAttributes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Attribute` enum('IMAGE','LABEL','OTHER','ISBN','MANUFACTURER','EXPIRE','TAGS') COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=24 ;

--
-- Dumping data for table `shop_productAttributes`
--

INSERT INTO `shop_productAttributes` (`ID`, `CustomerID`, `ProductID`, `Attribute`, `Value`) VALUES
(1, 1, 4, 'LABEL', 'test'),
(2, 1, 4, 'TAGS', 'wash device'),
(3, 1, 5, 'TAGS', 'light bulb'),
(4, 1, 5, 'LABEL', 'smth elese'),
(5, 1, 4, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(6, 1, 5, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(7, 1, 6, 'IMAGE', 'http://cmsresources.windowsphone.com/windowsphone/en-gb/Phones/Lumia820/Phone280x280.png'),
(8, 1, 7, 'IMAGE', 'http://www.hp-laptops.org/wp-content/uploads/2011/12/HP-Probook-5330m-Images.jpg'),
(9, 1, 8, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(10, 1, 9, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(11, 1, 10, 'IMAGE', 'http://blogs.independent.co.uk/wp-content/uploads/2013/01/ubuntu-for-phones.jpg'),
(12, 1, 11, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(13, 1, 12, 'IMAGE', 'http://www.informetop.com/wp-content/uploads/2012/06/TV-LCD.jpg'),
(14, 1, 6, 'LABEL', 'smth elese'),
(15, 1, 7, 'LABEL', 'smth elese'),
(16, 1, 4, 'IMAGE', 'http://jomax-international.com/files/products/images/LCD-3.jpg'),
(17, 1, 4, 'IMAGE', 'http://www2.hull.ac.uk/student/images/lcd-tv.jpg'),
(18, 1, 4, 'IMAGE', 'http://www.magnet.ru/pictures/17-662-201306151235480.jpg'),
(19, 1, 4, 'IMAGE', 'http://www.nine220volts.com/images/22LH20R.jpg'),
(20, 1, 4, 'IMAGE', 'http://img.elmir.ua/img/243547/3000/2000/monitor_lcd_22_philips_224e5qsb_01.jpg'),
(21, 1, 4, 'IMAGE', 'http://i00.i.aliimg.com/photo/v3/485808738/FHD_1080p_42_inch_lcd_tv_led.jpg'),
(22, 1, 4, 'IMAGE', 'http://img1.elmir.ua/img/235695/1960/1280/monitor_lcd_23_samsung_s23c570hs_ls23c570hs.jpg'),
(23, 1, 4, 'IMAGE', 'http://www.blogcdn.com/www.engadget.com/media/2010/07/acer-s1-lcd-monitor.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `shop_productPrices`
--

DROP TABLE IF EXISTS `shop_productPrices`;
CREATE TABLE IF NOT EXISTS `shop_productPrices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

--
-- Dumping data for table `shop_productPrices`
--

INSERT INTO `shop_productPrices` (`ID`, `CustomerID`, `ProductID`, `Price`, `DateCreated`) VALUES
(1, 1, 4, 8.25, '2013-10-01 00:00:00'),
(2, 1, 4, 11.25, '2013-10-02 00:00:00'),
(3, 1, 4, 5.50, '2013-10-03 00:00:00'),
(4, 1, 4, 2.45, '2013-10-04 00:00:00'),
(5, 1, 4, 5.45, '2013-10-05 00:00:00'),
(6, 1, 4, 9.45, '2013-10-06 00:00:00'),
(7, 1, 4, 10.95, '2013-10-07 00:00:00'),
(8, 1, 4, 14.25, '2013-10-08 00:00:00'),
(9, 1, 4, 13.25, '2013-10-09 00:00:00'),
(10, 1, 3, 12.25, '2013-10-10 00:00:00'),
(11, 1, 4, 10.25, '2013-10-11 00:00:00'),
(12, 1, 3, 1.25, '2013-10-12 00:00:00'),
(13, 1, 3, 19.25, '2013-10-13 00:00:00'),
(14, 1, 4, 7.00, '2013-10-14 00:00:00'),
(15, 1, 4, 4.00, '2013-10-15 00:00:00'),
(16, 1, 4, 2.00, '2013-10-16 00:00:00'),
(17, 1, 4, 1.50, '2013-10-17 00:00:00'),
(18, 1, 4, 11.50, '2013-10-18 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shop_products`
--

DROP TABLE IF EXISTS `shop_products`;
CREATE TABLE IF NOT EXISTS `shop_products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `OriginID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `Specifications` text COLLATE utf8_bin,
  `Model` text COLLATE utf8_bin,
  `SKU` text COLLATE utf8_bin,
  `Price` decimal(10,2) NOT NULL,
  `Status` enum('ACTIVE','REMOVED','OUTOFSTOCK','COMINGSOON') COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OriginID` (`OriginID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products' AUTO_INCREMENT=29 ;

--
-- Dumping data for table `shop_products`
--

INSERT INTO `shop_products` (`ID`, `CustomerID`, `CategoryID`, `OriginID`, `Name`, `ExternalKey`, `Description`, `Specifications`, `Model`, `SKU`, `Price`, `Status`, `DateCreated`, `DateUpdated`) VALUES
(3, 1, 1, 1, 'TES 1', 'tes1', 'test test 33', 'test test 33', 'test test 33', 'test test 33', 213.00, 'ACTIVE', '0000-00-00 00:00:00', '2013-09-30 12:21:56'),
(4, 1, 1, 5, 'LCD S32DV', 'lcds32dv', 'LCD S32DV Description', '', 'S32DV', 'S32DV11111', 100.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(5, 1, 1, 2, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 17.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(6, 1, 1, 1, 'I AM HIDDEN PRODUCT', 'test2', 'test test', 'test test', 'test test', 'test test', 36.00, 'REMOVED', '0000-00-00 00:00:00', '2013-09-30 12:21:56'),
(7, 1, 4, 1, 'Ложки', 'logku', 'Опис тут', '', 'L100', 'ALLL1200100', 46.25, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(8, 1, 16, 7, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 56.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(9, 1, 15, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 71.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(10, 1, 13, 8, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 171.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(11, 1, 23, 2, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 37.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(12, 1, 3, 3, 'AAA S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 17.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(13, 1, 1, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 355.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(14, 1, 27, 3, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 68.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(15, 1, 1, 3, 'CCC S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 85.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(16, 1, 1, 1, 'EEE S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 554.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(17, 1, 15, 6, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(18, 0, 1, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 65.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(19, 0, 16, 6, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(20, 0, 1, 1, 'BBB S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 55.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(21, 0, 14, 8, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 45.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(22, 0, 14, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 65.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(23, 0, 14, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 83.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(24, 0, 1, 7, 'GGG S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(25, 0, 23, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(26, 0, 21, 8, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(27, 0, 1, 1, 'LCD S48DV', 'lcds48dv', 'LCD S48DV Description', '', 'S48DV', 'S48DV222222', 7.00, 'ACTIVE', '2013-08-27 02:28:56', '2013-08-27 02:28:56'),
(28, 0, 7, 2, 'test offer linked', 'fsdfsdfs', 'test offer linked', 'test offer linked', 'test offer linked', NULL, 0.00, 'ACTIVE', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shop_relations`
--

DROP TABLE IF EXISTS `shop_relations`;
CREATE TABLE IF NOT EXISTS `shop_relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductA_ID` int(11) NOT NULL,
  `ProductB_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductB_ID` (`ProductB_ID`),
  KEY `ProductA_ID` (`ProductA_ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_specifications`
--

DROP TABLE IF EXISTS `shop_specifications`;
CREATE TABLE IF NOT EXISTS `shop_specifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Fields` text COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shop_specifications`
--

INSERT INTO `shop_specifications` (`ID`, `CustomerID`, `Name`, `Fields`, `DateCreated`, `DateUpdated`) VALUES
(1, 0, 'TV', 'Screen\\r\\nDPI', '2013-08-27 02:25:05', '2013-08-27 02:25:05');

-- --------------------------------------------------------

--
-- Stand-in structure for view `test2`
--
DROP VIEW IF EXISTS `test2`;
CREATE TABLE IF NOT EXISTS `test2` (
`pNAME` varchar(200)
,`cName` varchar(100)
);
-- --------------------------------------------------------

--
-- Table structure for table `writer_documents`
--

DROP TABLE IF EXISTS `writer_documents`;
CREATE TABLE IF NOT EXISTS `writer_documents` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Price` double NOT NULL,
  `Discount` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `writer_documents`
--

INSERT INTO `writer_documents` (`ID`, `Name`, `Price`, `Discount`) VALUES
(7, 'Essay Simple', 0, 0),
(8, 'Essay Smart', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_invoices`
--

DROP TABLE IF EXISTS `writer_invoices`;
CREATE TABLE IF NOT EXISTS `writer_invoices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `inv_type` enum('PAYMENT','REFUND') NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `sid` int(11) NOT NULL,
  `key` varchar(32) NOT NULL,
  `order_number` bigint(20) NOT NULL,
  `total` double NOT NULL,
  `merchant_order_id` varchar(32) NOT NULL,
  `credit_card_processed` varchar(1) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `writer_invoices`
--

INSERT INTO `writer_invoices` (`ID`, `inv_type`, `invoice_id`, `sid`, `key`, `order_number`, `total`, `merchant_order_id`, `credit_card_processed`, `email`, `phone`) VALUES
(3, 'PAYMENT', 4766310026, 1799160, '454DDCCBDADC5C822127E724FBF79D54', 4766310017, 139.95, 'cefbef67d8480b8f827630a5f41e437c', 'Y', 'andrew.ivaskevych@gmail.com', '123-456-7890 '),
(4, 'PAYMENT', 4767332839, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767332830, 39.55, 'c9198e0546fbab932edc1d22ffb69429', 'Y', 'soulcor@gmail.com', '123-456-7890 '),
(5, 'PAYMENT', 4767332839, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767332830, 39.55, 'c9198e0546fbab932edc1d22ffb69429', 'Y', 'soulcor@gmail.com', '123-456-7890 '),
(6, 'PAYMENT', 4767430597, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767430588, 39.55, 'ef2418b7c3741b07552cf094eb8eaaa6', 'Y', 'soulcor@gmail.com', '123-456-7890 ');

-- --------------------------------------------------------

--
-- Table structure for table `writer_messages`
--

DROP TABLE IF EXISTS `writer_messages`;
CREATE TABLE IF NOT EXISTS `writer_messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` text NOT NULL,
  `Message` text NOT NULL,
  `Owner` enum('WEBMASTER','WRITER','STUDENT','SYSTEM') NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `WriterID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ParentMessageID` int(11) DEFAULT NULL,
  `IsUnread` tinyint(1) NOT NULL DEFAULT '1',
  `IsPublic` tinyint(1) NOT NULL DEFAULT '0',
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `writer_messages`
--

INSERT INTO `writer_messages` (`ID`, `Subject`, `Message`, `Owner`, `StudentID`, `WriterID`, `OrderID`, `ParentMessageID`, `IsUnread`, `IsPublic`, `DateCreated`) VALUES
(4, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:09:36'),
(5, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:10:02'),
(6, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:11:51'),
(7, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:13:47'),
(8, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:21:20'),
(9, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:21:32'),
(10, 'Order Was Unassigned.', 'Sent to Task Queue', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:33:00'),
(11, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:40:48'),
(12, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:42:18'),
(13, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:42:31'),
(14, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:44:24'),
(15, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:45:57'),
(16, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:46:07'),
(17, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:46:54'),
(18, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:47:28'),
(19, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:47:44'),
(20, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:48:00'),
(21, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:49:02'),
(22, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:50:11'),
(23, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:51:19'),
(24, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:51:52'),
(25, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:52:20'),
(26, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:52:30'),
(27, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:53:21'),
(28, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:53:33'),
(29, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:54:14'),
(30, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:09:46'),
(31, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:11:17'),
(32, 'Resolution Document Is Modified', 'Document Link: hfghfghgfhg', 'SYSTEM', 1, NULL, 3, NULL, 1, 0, '2012-07-26 23:15:15'),
(33, 'Need Approval', 'Waiting for approval.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:15:20'),
(34, 'Order Was Approved To Review.', '<div>PublicStatus: PENDING</div>\n<div>InternalStatus: APPROVED</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:18:47'),
(35, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: PENDING</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:20:47'),
(36, 'Order Was Approved To Review.', '<div>PublicStatus: PENDING</div>\n<div>InternalStatus: APPROVED</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:21:30'),
(37, 'Buyer Wants To Rework', 'Ask owner for more details to rework.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-26 23:24:31'),
(38, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:28:28'),
(39, 'test student', 'test student test student test student test student', 'STUDENT', 5, NULL, 3, NULL, 1, 1, '2012-07-26 23:31:27'),
(40, 'wrietr answer', 'wrietr answerwrietr answerwrietr answerwrietr answerwrietr answer', 'WRITER', NULL, 1, 3, NULL, 1, 1, '2012-07-26 23:34:39'),
(41, 'wrietr answer', 'wrietr answerwrietr answerwrietr answerwrietr answerwrietr answer', 'WRITER', NULL, 1, 3, NULL, 0, 1, '2012-07-26 23:59:27'),
(42, 'Buyer Wants Refund', 'You must clarify the reason of refund action.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:27:21'),
(43, 'Order Was Updated.', '<div>PublicStatus: TO REFUND</div>\n<div>InternalStatus: CLOSED</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:37:54'),
(44, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:43:10'),
(45, 'Buyer Wants Refund', 'You must clarify the reason of refund action.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:43:16'),
(46, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:46:55'),
(47, 'Order Was Updated.', '<div>PublicStatus: TO REFUND</div>\n<div>InternalStatus: CLOSED</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:49:50'),
(48, 'Sources were changed.', '1<br>3', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:06:16'),
(49, 'Sources were changed.', 'All sources were removed.', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:07:58'),
(50, 'Sources were changed.', 'demo 2', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:08:08'),
(51, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 4, NULL, 1, 0, '2012-07-27 16:22:37'),
(52, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 6, NULL, 3, NULL, 1, 0, '2012-07-27 16:27:45'),
(53, 'Order Is Closed', 'Owner has closed this order.', 'SYSTEM', 6, NULL, 3, NULL, 1, 0, '2012-07-27 16:30:17'),
(54, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 8, NULL, 1, 0, '2012-07-28 02:28:26'),
(55, 'Date Deadline is changed', '2012-07-28 03:30:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 18:58:03'),
(56, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:00:40'),
(57, 'Date Deadline is changed', '2012-07-29 22:00:00 => 2012-07-29 14:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:00:58'),
(58, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:01:07'),
(59, 'Date Deadline is changed', '2012-07-29 22:00:00 => 2012-07-29 19:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:11'),
(60, 'Date Deadline is changed', '2012-07-29 23:00:00 => 2012-07-29 20:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:40'),
(61, 'Date Deadline is changed', '2012-07-30 00:00:00 => 2012-07-29 14:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:59'),
(62, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 16:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:15:58'),
(63, 'Date Deadline is changed', '2012-07-29 20:00:00 => 2012-07-29 17:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 20:15:44'),
(64, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 8, NULL, 1, 0, '2012-07-29 20:27:11'),
(65, 'Order Is Closed', 'Owner has closed this order.', 'SYSTEM', 1, NULL, 8, NULL, 1, 0, '2012-07-29 20:37:11'),
(66, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 1, NULL, 8, NULL, 1, 0, '2012-07-29 21:46:04'),
(67, 'demo demo demo', 'demo demo demodemo demo demodemo demo demodemo demo demodemo demo demodemo demo demodemo demo demo', 'WEBMASTER', NULL, NULL, 8, NULL, 1, 0, '2012-07-29 22:47:39'),
(68, 'test', 'test', 'STUDENT', 1, NULL, 8, NULL, 0, 1, '2012-08-11 12:49:05'),
(69, 'test', 'test', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 12:51:06'),
(70, 'demo 3', 'demo 3 .. demo 3 .. demo 3 .. demo 3 .. demo 3 .. demo 3 .. ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 12:56:08'),
(71, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:05:26'),
(72, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:10:41'),
(73, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:12:42'),
(74, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:14:48'),
(75, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 10, NULL, 1, 0, '2012-08-20 23:22:15'),
(76, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 10, NULL, 1, 0, '2012-08-20 23:22:45'),
(77, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 00:12:38'),
(78, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: PENDING</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 00:12:53'),
(79, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 21:00:00 => 2012-08-30 12:00:00<br>Writer Local Time: <br>2012-08-31 08:00:00 => 2012-08-30 23:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:45:20'),
(80, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 22:00:00 => 2012-08-30 13:00:00<br>Writer Local Time: <br>2012-08-31 09:00:00 => 2012-08-31 00:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:46:42'),
(81, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 23:00:00 => 2012-08-30 14:00:00<br>Writer Local Time: <br>2012-08-31 10:00:00 => 2012-08-31 01:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:46:54'),
(82, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 20:00:00 => 2012-08-30 12:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 01:56:17'),
(83, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 01:56:27'),
(84, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 17:00:00 => 2012-08-30 16:00:00<br>Writer Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 22:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:03:07'),
(85, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 21:00:00 => 2012-08-30 05:00:00<br>Writer Local Time: <br>2012-08-30 22:00:00 => 2012-08-30 11:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:04:17'),
(86, 'Date Deadline is changed', 'Owner Local Time: <br>1346306400 => 1346284800<br>Writer Local Time: <br>1346310000 => 1346306400', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:23:50'),
(87, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 08:00:00 => 2012-08-30 12:00:00<br>Writer Local Time: <br>2012-08-30 09:00:00 => 2012-08-30 18:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:25:17'),
(88, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 17:00:00 => 2012-08-30 13:00:00<br>Writer Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 19:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:26:00'),
(89, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 14:00:00<br>Writer Local Time: <br>2012-08-30 19:00:00 => 2012-08-30 20:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:28:26'),
(90, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 14:00:00 => 2012-08-30 15:00:00<br>Writer Local Time: <br>2012-08-30 20:00:00 => 2012-08-30 21:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:29:07'),
(91, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 14:00:00 => 2012-08-30 15:00:00', 'SYSTEM', 13, 13, 18, NULL, 1, 0, '2012-08-22 22:29:40'),
(92, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 15:00:00 => 2012-08-30 16:00:00', 'SYSTEM', 13, 13, 18, NULL, 1, 0, '2012-08-22 22:30:52'),
(93, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 1, NULL, 1, 0, '2012-08-25 18:12:11'),
(94, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 4, NULL, 1, 0, '2012-08-25 19:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `writer_orders`
--

DROP TABLE IF EXISTS `writer_orders`;
CREATE TABLE IF NOT EXISTS `writer_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Description` text NOT NULL,
  `ResolutionDocumentLink` text,
  `PublicStatus` enum('NEW','IN PROGRESS','PENDING','CLOSED','REWORK','REOPEN','TO REFUND') NOT NULL,
  `InternalStatus` enum('CLOSED','REJECTED','OPEN','APPROVED','PENDING') NOT NULL DEFAULT 'OPEN',
  `ReworkCount` int(11) NOT NULL,
  `DocumentID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `PriceID` int(11) NOT NULL,
  `Level` enum('High School','College','University') NOT NULL DEFAULT 'High School',
  `Format` enum('MLA','APA','Chicago','Turabian') NOT NULL DEFAULT 'MLA',
  `Pages` int(11) NOT NULL,
  `UseSources` int(11) NOT NULL DEFAULT '0',
  `StudentID` int(11) NOT NULL,
  `WriterID` int(11) NOT NULL,
  `Price` double NOT NULL,
  `Credits` double NOT NULL,
  `Discount` double NOT NULL,
  `RefundToken` varchar(32) DEFAULT NULL,
  `OrderToken` varchar(32) NOT NULL,
  `TimeZone` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateDeadline` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `OrderToken` (`OrderToken`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `writer_orders`
--

INSERT INTO `writer_orders` (`ID`, `Title`, `Description`, `ResolutionDocumentLink`, `PublicStatus`, `InternalStatus`, `ReworkCount`, `DocumentID`, `SubjectID`, `PriceID`, `Level`, `Format`, `Pages`, `UseSources`, `StudentID`, `WriterID`, `Price`, `Credits`, `Discount`, `RefundToken`, `OrderToken`, `TimeZone`, `DateCreated`, `DateDeadline`) VALUES
(1, 'xxxgfdgdg dfg', '', NULL, 'NEW', 'OPEN', 0, 7, 10, 1, 'High School', 'MLA', 1, 0, 1, 0, 39.55, 10, 0, '', 'f03a4a9c48b90e54b99f84014dcdb787', 'Kwajalein', '2012-08-26 22:51:09', '2012-08-29 12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `writer_prices`
--

DROP TABLE IF EXISTS `writer_prices`;
CREATE TABLE IF NOT EXISTS `writer_prices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Price` double NOT NULL,
  `Hours` double NOT NULL,
  `Weeks` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `writer_prices`
--

INSERT INTO `writer_prices` (`ID`, `Name`, `Price`, `Hours`, `Weeks`) VALUES
(1, 'Flash - in 8 - hours $39.55/page ', 39.55, 8, 0),
(2, 'Save money - two weeks - $10.55/page', 10.55, 0, 2),
(3, 'Economical - one week - $12.55/page', 12.55, 0, 1),
(4, 'Regular - in 96 hours - $15.55/page', 15.55, 96, 0),
(5, 'Fast - in 48 hours - 18.55/page ', 18.55, 48, 0),
(6, 'Emergency - in 24 - hours $24.55/page ', 24.55, 24, 0),
(7, 'Rush - in 12 - hours $34.55/page ', 34.55, 12, 0),
(8, 'demo price', 0.01, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_sale`
--

DROP TABLE IF EXISTS `writer_sale`;
CREATE TABLE IF NOT EXISTS `writer_sale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Description` text,
  `Sample` text,
  `Pages` double NOT NULL,
  `Price` double NOT NULL,
  `DocumentURL` text,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `writer_sale`
--

INSERT INTO `writer_sale` (`ID`, `Title`, `Description`, `Sample`, `Pages`, `Price`, `DocumentURL`, `DateCreated`) VALUES
(1, 'demo 1', '', '<h1>demo de<span style="text-decoration: underline;">mo dem<strong>o</strong></span> demo demo</h1>\r\n<p>sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt;&nbsp;</p>\r\n<ol>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some ite</li>\r\n</ol>\r\n<h1 style="text-align: left;"><br></h1><h1 style="text-align: left;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<img src="https://www.google.com.ua/logos/classicplus.png" alt="" align="none" style="font-size: 12px; font-weight: normal; text-align: center; "><br></h1><h1 style="text-align: left;"><br></h1><h1 style="text-align: left;"><br></h1><h1 style="text-align: left;"><img src="../static/wysiwyg/tiny_mce/themes/advanced/skins/default/img/buttons.png" alt="" width="94" height="78"></h1><div><br></div><div style="text-align: center;"><br></div><p style="text-align: center;"><br></p><p style="text-align: center;"><br></p>\r\n<p style="padding-left: 30px;">TROLOLOLO TROLOLOLO TROLOLOLO TROLOLOLO&nbsp;</p>\r\n<p style="padding-left: 30px;">&nbsp;</p>\r\n<p style="padding-left: 30px;">A TUT MAE BUTU SHE BILSHE TEXTU!!!! :)))) TROLOLOLO!!!! OTAk&nbsp;</p>', 1, 2.55, '', '0000-00-00 00:00:00'),
(3, 'XXXXXXX', 'xxxxxxx', '<p>demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;</p>', 99, 99.99, 'http://essay-about.mpws.com/mpws/writer.html?display=sale&action=create', '0000-00-00 00:00:00'),
(4, 'ZZZZZ', 'zzzzzzz', '<p><strong>SECOND DEMO</strong></p>', 3, 12.99, 'http://essay-about.mpws.com/mpws/writer.html?display=sale&action=create', '2012-07-25 22:02:11'),
(5, 'demo sale 1', 'demo deo demo', '<p>demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;</p>', 2, 0, 'http://google.com', '2012-07-26 14:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `writer_sales`
--

DROP TABLE IF EXISTS `writer_sales`;
CREATE TABLE IF NOT EXISTS `writer_sales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SaleID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT '1',
  `SalesToken` varchar(32) DEFAULT NULL,
  `RefundToken` varchar(32) DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `writer_sales`
--

INSERT INTO `writer_sales` (`ID`, `SaleID`, `StudentID`, `IsActive`, `SalesToken`, `RefundToken`, `DateCreated`) VALUES
(1, 1, NULL, 1, '45d7c03dcf78945415001c8a7caf9ca6', NULL, '2012-08-12 13:41:29'),
(2, 1, NULL, 1, '5a007b45213347bcc00d4c38cafd66c4', NULL, '2012-08-12 13:42:12'),
(3, 3, NULL, 1, 'c2e2d1dcb06da2ffede409e1f46e1d84', NULL, '2012-08-12 13:48:19'),
(4, 3, NULL, 1, '44e9d81056dc2aa709e97d0cadbf32d1', NULL, '2012-08-12 13:49:52'),
(5, 3, NULL, 1, '780059e35995533eee246c0c4faea86c', NULL, '2012-08-12 13:51:41'),
(6, 3, NULL, 1, 'ed743e1423c195c29b8123a257e7f0b8', NULL, '2012-08-12 13:53:47'),
(7, 1, NULL, 1, '2b7c72fbbcad068db81785a06dd3ff79', NULL, '2012-08-12 13:54:05'),
(8, 4, NULL, 1, '8e40a3f19d34e280ff2eb45e4d2564cb', NULL, '2012-08-15 22:33:57'),
(9, 4, NULL, 1, '2b6d84c2434d7986e2cf4128bfd2279f', NULL, '2012-08-15 22:37:24'),
(12, 4, NULL, 1, '91c402b9c86f1024e2acb29f2e65ad6a', NULL, '2012-08-26 00:44:32'),
(13, 3, NULL, 1, '02dc4bf1c057b8b9d3fee1b2892d2fa3', NULL, '2012-08-26 00:44:33'),
(14, 1, NULL, 1, 'e2097526ac48e7ab97a9690c75a96d01', NULL, '2012-08-26 00:44:35'),
(15, 4, NULL, 1, '1511c6a3f9763fcaecca648bd49fff24', NULL, '2012-08-26 00:45:01'),
(16, 3, NULL, 1, '400ea371dbe5304d4995ad042243f3bc', NULL, '2012-08-26 00:45:02'),
(17, 1, NULL, 1, '8b0a18d40dcb0627709948ae8735f8f2', NULL, '2012-08-26 00:45:03'),
(18, 4, NULL, 1, '935f5fa2f2f1ec96f25d92f8029a0bd5', NULL, '2012-08-26 00:45:51'),
(19, 3, NULL, 1, '4c25c37f3daf9e07308fce81d7d268b8', NULL, '2012-08-26 00:45:53'),
(20, 1, NULL, 1, '89b0ebc56b34d5a9da97899cd7d9768c', NULL, '2012-08-26 00:45:54'),
(21, 4, NULL, 1, '7cf6eca5b695611572e10086220873e5', NULL, '2012-08-26 00:47:47'),
(22, 3, NULL, 1, '8e9e94ea3a16fe64bb3fc3559907b151', NULL, '2012-08-26 00:47:48'),
(23, 1, NULL, 1, 'ac444eb0649ec6aa29c06715bcc2da6f', NULL, '2012-08-26 00:47:50'),
(24, 4, NULL, 1, '0d3f2dc06ce00adf54f122aed5c087e4', NULL, '2012-08-26 01:15:14'),
(25, 3, NULL, 1, '2d51bf48cfa5b9e29a0d124a81ed873a', NULL, '2012-08-26 01:15:15'),
(26, 1, NULL, 1, '0960900a36ef633bded466222805563d', NULL, '2012-08-26 01:15:16');

-- --------------------------------------------------------

--
-- Table structure for table `writer_sources`
--

DROP TABLE IF EXISTS `writer_sources`;
CREATE TABLE IF NOT EXISTS `writer_sources` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderToken` varchar(32) NOT NULL,
  `SourceURL` text NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `writer_students`
--

DROP TABLE IF EXISTS `writer_students`;
CREATE TABLE IF NOT EXISTS `writer_students` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Login` text NOT NULL,
  `Password` text NOT NULL,
  `Email` text,
  `IM` text NOT NULL,
  `Phone` text,
  `UserToken` varchar(32) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `Billing_FirstName` text,
  `Billing_LastName` text,
  `Billing_Email` text,
  `Billing_Phone` text,
  `Billing_Address` text,
  `Billing_City` text,
  `Billing_State` text,
  `Billing_PostalCode` text,
  `Billing_Country` text,
  `IsTemporary` tinyint(1) NOT NULL,
  `ModifiedBy` enum('SYSTEM','USER') NOT NULL DEFAULT 'SYSTEM',
  `TimeZone` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserToken` (`UserToken`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `writer_subjects`
--

DROP TABLE IF EXISTS `writer_subjects`;
CREATE TABLE IF NOT EXISTS `writer_subjects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `writer_subjects`
--

INSERT INTO `writer_subjects` (`ID`, `Name`) VALUES
(10, 'Subject One'),
(11, 'Subject Two');

-- --------------------------------------------------------

--
-- Table structure for table `writer_writers`
--

DROP TABLE IF EXISTS `writer_writers`;
CREATE TABLE IF NOT EXISTS `writer_writers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Login` text NOT NULL,
  `Password` text NOT NULL,
  `Subjects` text,
  `CardNumber` text NOT NULL,
  `CardType` enum('VISA','MC','Disc','AmEx','Diners') NOT NULL DEFAULT 'VISA',
  `University` text,
  `Email` text,
  `IM` text,
  `Phone` text,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `ModifiedBy` enum('SYSTEM','USER') NOT NULL DEFAULT 'SYSTEM',
  `TimeZone` varchar(50) NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `writer_writers`
--

INSERT INTO `writer_writers` (`ID`, `Name`, `Login`, `Password`, `Subjects`, `CardNumber`, `CardType`, `University`, `Email`, `IM`, `Phone`, `Active`, `IsOnline`, `ModifiedBy`, `TimeZone`, `DateLastAccess`, `DateCreated`) VALUES
(1, 'DEMO WRITER', 'test', '098f6bcd4621d373cade4e832627b4f6', 'tes test testtt', '0000-0000-0000-0000', 'VISA', 'harvard', 'soulcor@gmail.com', 'skype.name.1', '123-123-1234', 1, 1, 'USER', 'Pacific/Tahiti', '2012-08-28 23:09:16', '2012-07-23 01:30:40'),
(4, 'DEMO WRITER LOCAL', 'writer1', '098f6bcd4621d373cade4e832627b4f6', '', '', 'VISA', '', 'my@mail.com', '', '', 1, 1, 'SYSTEM', 'Europe/London', '2012-08-25 19:15:23', '2012-08-09 22:32:54'),
(5, 'jfjhfjgjgfjfgjg', 'dfdsfsdfsdf', '552d0d8f3bea399c22d9ffc40f68d560', '', '', 'VISA', '', 'my@mail.com', '', '', 1, 0, 'SYSTEM', '', '2012-08-09 22:48:17', '2012-08-09 22:48:17');

-- --------------------------------------------------------

--
-- Structure for view `test2`
--
DROP TABLE IF EXISTS `test2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `test2` AS select `p`.`Name` AS `pNAME`,`c`.`Name` AS `cName` from (`shop_products` `p` left join `shop_categories` `c` on((`p`.`CategoryID` = `c`.`ID`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mpws_accountAddresses`
--
ALTER TABLE `mpws_accountAddresses`
  ADD CONSTRAINT `mpws_accountAddresses_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mpws_accounts`
--
ALTER TABLE `mpws_accounts`
  ADD CONSTRAINT `mpws_accounts_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `mpws_jobs`
--
ALTER TABLE `mpws_jobs`
  ADD CONSTRAINT `mpws_jobs_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mpws_subscripers`
--
ALTER TABLE `mpws_subscripers`
  ADD CONSTRAINT `mpws_subscripers_ibfk_2` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mpws_subscripers_ibfk_3` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mpws_uploads`
--
ALTER TABLE `mpws_uploads`
  ADD CONSTRAINT `mpws_uploads_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mpws_users`
--
ALTER TABLE `mpws_users`
  ADD CONSTRAINT `mpws_users_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_boughts`
--
ALTER TABLE `shop_boughts`
  ADD CONSTRAINT `shop_boughts_ibfk_5` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_boughts_ibfk_6` FOREIGN KEY (`OrderID`) REFERENCES `shop_orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD CONSTRAINT `shop_categories_ibfk_4` FOREIGN KEY (`RootID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_categories_ibfk_5` FOREIGN KEY (`ParentID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_categories_ibfk_6` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_categories_ibfk_7` FOREIGN KEY (`SchemaID`) REFERENCES `shop_specifications` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `shop_commands`
--
ALTER TABLE `shop_commands`
  ADD CONSTRAINT `shop_commands_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_currency`
--
ALTER TABLE `shop_currency`
  ADD CONSTRAINT `shop_currency_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_offers`
--
ALTER TABLE `shop_offers`
  ADD CONSTRAINT `shop_offers_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_orders`
--
ALTER TABLE `shop_orders`
  ADD CONSTRAINT `shop_orders_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_orders_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_origins`
--
ALTER TABLE `shop_origins`
  ADD CONSTRAINT `shop_origins_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_productAttributes`
--
ALTER TABLE `shop_productAttributes`
  ADD CONSTRAINT `shop_productAttributes_ibfk_3` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_productAttributes_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_productPrices`
--
ALTER TABLE `shop_productPrices`
  ADD CONSTRAINT `shop_productPrices_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_products`
--
ALTER TABLE `shop_products`
  ADD CONSTRAINT `shop_products_ibfk_3` FOREIGN KEY (`CategoryID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_products_ibfk_4` FOREIGN KEY (`OriginID`) REFERENCES `shop_origins` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_products_ibfk_5` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_relations`
--
ALTER TABLE `shop_relations`
  ADD CONSTRAINT `shop_relations_ibfk_3` FOREIGN KEY (`ProductA_ID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_relations_ibfk_4` FOREIGN KEY (`ProductB_ID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `shop_relations_ibfk_5` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_specifications`
--
ALTER TABLE `shop_specifications`
  ADD CONSTRAINT `shop_specifications_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;