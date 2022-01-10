<?xml version='1.0' encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:param name="date" />
  <xsl:param name="time" />
  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements="*" />

  <xsl:template match="/">
    <div class="meteo--list">
      <xsl:apply-templates select="previsions/echeance" />
    </div>
  </xsl:template>

  <!-- Render HTML element -->
  <xsl:template match="echeance" mode="render">
    <div>
      <xsl:choose>
          <xsl:when test="number(substring(@timestamp, 12, 2)) &lt; number($time)">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item item-passed'"/>
            </xsl:attribute>
          </xsl:when>
          <xsl:otherwise>
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item'"/>
            </xsl:attribute>
          </xsl:otherwise>
        </xsl:choose>
      <div class="meteo--item_time">
        <xsl:value-of select="substring(@timestamp, 6, 11)" />
      </div>
      <!-- Température -->
      <xsl:variable name="temperature">
          <xsl:value-of select="round(temperature/level[@val='2m'] - 273.15)"/>
      </xsl:variable>
      <div>
        <xsl:choose>
          <xsl:when test="$temperature &gt; 30">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-very-hot'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-full"></i>
          </xsl:when>
          <xsl:when test="$temperature &gt; 20">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-hot'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-three-quarters"></i>
          </xsl:when>
          <xsl:when test="$temperature &gt; 10">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-half"></i>
          </xsl:when>
          <xsl:when test="$temperature &gt; 0">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-cold'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-quarter"></i>
          </xsl:when>
          <xsl:otherwise>
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_temperature t-very-cold'"/>
            </xsl:attribute>
            <i class="fas fa-thermometer-empty"></i>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:value-of select="$temperature" />
      </div>
      <!-- Status -->
      <div class="meteo--item_status">
        <xsl:choose>
          <xsl:when test="pluie &gt; 0">
            <i class="fas fa-cloud-rain"></i>
          </xsl:when>
          <xsl:otherwise>
            <i class="fas fa-sun"></i>
          </xsl:otherwise>
        </xsl:choose>
        <!-- Neige -->
        <xsl:choose>
          <xsl:when test="risque_neige = 'oui'">
            <i class="fas fa-snowflake"></i>
          </xsl:when>
        </xsl:choose>
      </div>
      <!-- Humidité -->
      <div>
        <xsl:choose>
          <xsl:when test="humidite/level &gt; 75">
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_humid h-humid'"/>
            </xsl:attribute>
          </xsl:when>
          <xsl:otherwise>
            <xsl:attribute name="class">
              <xsl:value-of select="'meteo--item_humid'"/>
            </xsl:attribute>
          </xsl:otherwise>
        </xsl:choose>
        <i class="fas fa-tint"></i>
        <xsl:value-of select="humidite/level" />
      </div>
      <!-- Conseils -->
      <div class="meteo--item_advice">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <xsl:choose>
              <xsl:when test="$temperature &lt; 10">
                Uniquement si t'a pas d'autres solutions !
              </xsl:when>
              <xsl:otherwise>
                Sort la veste et prend un parapluie dans le sac.
              </xsl:otherwise>
            </xsl:choose>
          </xsl:when>
          <xsl:otherwise>
              <xsl:choose>
                <xsl:when test="$temperature &lt; 10">
                  Prend un bonnet... On sait jamais...
                </xsl:when>
                <xsl:otherwise>
                  Les conditions sont idéales
                </xsl:otherwise>
              </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
      </div>
    </div>
  </xsl:template>
  <!-- Various echances -->
  <xsl:template match="echeance">
    <xsl:variable name="datetime">
      <xsl:value-of select="substring(@timestamp, 0, 17)" />
    </xsl:variable>

    <xsl:choose>
      <xsl:when test="
        $datetime=concat($date, ' 04:00')
        or $datetime=concat($date, ' 07:00')
        or $datetime=concat($date, ' 13:00')
        or $datetime=concat($date, ' 16:00')
        or $datetime=concat($date, ' 19:00')
      ">
        <xsl:apply-templates select="." mode="render" /> 
      </xsl:when>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>