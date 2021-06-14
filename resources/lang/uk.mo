��    8      �  O   �      �  3   �  z     l   �     �  �     2   �  /   �  8   �  ^   5  ]   �  1   �  !   $  %   F  c   l  ?   �  6   	  M   G	  `   �	  �   �	  H   �
     �
  /   �
  T   )  U   ~  k   �     @  =   T  |   �  a        q  @   �     �     �     �          $  ?   9  3   y  y   �  x   '  �   �  "   >  F   a  �   �     3  /   S     �     �  ]   �     �             /   ;     k  $   q    �  r   �  �     �     )       /  ^   1  f   �  l   �  �   d  �   �  k   �  E   4  L   z  	  �  c   �  W   5  �   �  �     H  �  �   #      �   j   �   x   A!  �   �!  �   I"     #  w   <#    �#  �   �$  7   Z%     �%  9   &  E   L&  ?   �&  ,   �&  8   �&  X   8'  ;   �'  �   �'  �   �(  1  �)  U   �*  d   +  �   z+  R   x,  _   �,     +-     8-  �   L-     �-  >   .  ?   R.  B   �.     �.  D   �.         /      (      8           -          .      #   &   6      ,   2      	   3                  '           
                    !   4                 "      *      7   +      0                     )         5                                             $             1       %       A module integrating GOV (historic gazetteer) data. A module integrating GOV (historic gazetteer) data. Enhances places with GOV data via the extended 'Facts and events' tab. According to the current GOV specification, settlements are not supposed to be parents of other settlements. Administrative levels All data (except for the mapping of places to GOV ids, which has to be done manually) is retrieved from the GOV server and cached internally. Allow objects of type 'confederation' in hierarchy Allow objects of type 'settlement' in hierarchy Compact display (administrative levels only as tooltips) Consequently, place hierarchy information can only be changed indirectly, via the GOV website. Display a tooltip indicating the source of the GOV id. This is intended mainly for debugging. For events with a date range, use the median date GOV id for %1$s has been removed. GOV id for %1$s has been set to %2$s. GOV ids are stored outside GEDCOM data by default, but ids stored via _GOV tags are also supported. GOV place hierarchy for %1$s has been reloaded from GOV server. GOV place hierarchy has been reloaded from GOV server. If this option is checked, you usually want to disable the following option.  In particular, the Shared Places custom module may be used to manage GOV ids within GEDCOM data. In this case, the GOV ids are stored in a separate database table, which has to be managed manually when moving the respective tree to a different webtrees installation.  Invalid GOV id! Valid GOV ids are e.g. 'EITTZE_W3091', 'object_1086218'. Local GOV data Mappings of places to GOV ids are not affected. Note: Ultimately it's probably preferable to correct the respective GOV data itself. Objects of this type arguably do not strictly belong to the administrative hierarchy. Otherwise, the start date is used (this is more consistent with other date-based calculations in webtrees). Outside GEDCOM data Outside GEDCOM data - editable by anyone (including visitors) Particularly useful in order to manage GOV ids via the Shared Places module. Ids are stored and exportable via GEDCOM tags.  Place hierarchies are displayed historically, i.e. according to the date of the respective event. Place text and links Reset GOV id (outside GEDCOM) and reload the GOV place hierarchy Reset GOV id for %1$s Set GOV id (outside GEDCOM) Set GOV id for %1$s Show GOV hierarchy for Show additional info Subsequently, all data is retrieved again from the GOV server.  The GOV server seems to be temporarily unavailable. This option mainly exists for demo servers and is not recommended otherwise. It has precedence over the preceding option. This policy hasn't been strictly followed though. Check this option if you end up with incomplete hierarchies otherwise. Uncheck this option if you do not want objects such as the European Union or the Holy Roman Empire to appear in hierarchies as parents of sovereign entities. Use place names and links from GOV Usually only required in case of substantial changes of the GOV data.  When this option is disabled, an alternative edit control is provided, which still allows to reload place hierarchies from the GOV server. Where to edit and store GOV ids Within GEDCOM data (via other custom modules).  both date of event for events without a date, present time hierarchy will be used regardless of this preference. present time reload the GOV place hierarchy reset all cached data once this place does not exist at this point in time today unknown GOV type (newly introduced?) Project-Id-Version: Ukrainian (Vesta Webtrees Custom Modules)
Report-Msgid-Bugs-To: 
PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE
Last-Translator: FULL NAME <EMAIL@ADDRESS>
Language-Team: Ukrainian <https://hosted.weblate.org/projects/vesta-webtrees-custom-modules/vesta-gov4webtrees/uk/>
Language: uk
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2;
X-Generator: Weblate 4.7-dev
 Модуль, що інтегрує дані GOV (історичний географічний довідник). Модуль з інтеграції даних GOV (історичний географічний довідник). Покращує місця з даними GOV за допомогою розширеної вкладки "Факти та події". Відповідно до чинної специфікації GOV, населені пункти не повинні бути батьківськими по відношенню до інших населених пунктів. Адміністративні рівні Усі дані (за винятком відображення місць до ідентифікаторів GOV, яке потрібно зробити вручну) отримуються з сервера GOV та кешуються всередині. Дозволити об’єкти типу „конфедерація” в ієрархії Дозволити об’єкти типу «поселення, колонія» в ієрархії Компактний вигляд (адміністративні рівні лише як підказки) Отже, інформацію про ієрархію місць можна змінювати лише побічно через веб-сайт GOV. Відобразіть підказку із зазначенням джерела ідентифікатора GOV. Це призначено головним чином для налагодження. Для подій із діапазоном дат використовуйте серединну дату Ідентифікатор GOV для %1$s було видалено. Ідентифікатор GOV для %1$s встановлено як %2$s. Ідентифікатори GOV за замовчуванням зберігаються поза даними GEDCOM, але також підтримуються ідентифікатори, що зберігаються за допомогою тегів _GOV. Ієрархію місця GOV для %1$s перезавантажено із сервера GOV. Ієрархію місця GOV перезавантажено із сервера GOV. Якщо цю опцію встановлено, зазвичай потрібно вимкнути наступну опцію.  Зокрема, користувацький модуль Shared Places може використовуватися для управління ідентифікаторами GOV у даних GEDCOM. У цьому випадку ідентифікатори GOV зберігаються в окремій таблиці бази даних, якою потрібно керувати вручну під час переміщення відповідного дерева до іншої інсталяції веб-дерев.  Недійсний ідентифікатор GOV! Приклад дійсних ідентифікаторів GOV: 'EITTZE_W3091', 'object_1086218'. Місцеві дані GOV На відображення місць до ідентифікаторів GOV це не впливає. Примітка: Зрештою, мабуть, краще відкоригувати відповідні дані GOV. Можливо, об’єкти цього типу не суворо належать до адміністративної ієрархії. В іншому випадку використовується дата початку (це більш узгоджується з іншими розрахунками на основі дати на webtrees). Поза даними GEDCOM Зовнішні дані GEDCOM - редагуються будь-ким (включаючи відвідувачів) Особливо корисно для управління ідентифікаторами GOV за допомогою модуля Shared Places. Ідентифікатори зберігаються та експортуються через теги GEDCOM.  Ієрархії місць відображаються історично, тобто відповідно до дати відповідної події. Розмістіть текст та посилання Скинути ідентифікатор GOV (поза GEDCOM) і перезавантажте ієрархію місць GOV Скинути ідентифікатор GOV для %1$s Встановити ідентифікатор GOV (поза GEDCOM) Встановити ідентифікатор GOV для %1$s Показати ієрархію GOV для Показати додаткову інформацію Згодом усі дані знову отримуються із сервера GOV.  Сервер GOV тимчасово недоступний. Ця опція в основному існує для демонстраційних серверів, інакше не рекомендується. Він має перевагу над попереднім варіантом. Однак ця політика не суворо дотримується. Позначте цей параметр, якщо в іншому випадку ви отримаєте неповні ієрархії. Зніміть цей прапорець, якщо ви не хочете, щоб такі об'єкти, як Європейський Союз або Священна Римська імперія, відображалися в ієрархіях як батьки суверенних утворень. Використовуйте назви місць та посилання від GOV Зазвичай це потрібно лише у разі значних змін даних GOV.  Коли цю опцію вимкнено, надається альтернативний контроль редагування, який все ще дозволяє перезавантажити ієрархії місць із сервера GOV. Де редагувати та зберігати ідентифікатори GOV В межах даних GEDCOM (через інші користувацькі модулі).  обидва дата події для подій без дати застосовуватиметься ієрархія поточного часу незалежно від цієї переваги. теперішній час перезавантажити ієрархію місця GOV скинути всі кешовані дані один раз на даний момент цього місця не існує сьогодні невідомий тип GOV (нещодавно введений?) 